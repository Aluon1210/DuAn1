<?php
namespace Models;
use Core\Model;
use Core\IdGenerator;
class Dashboard extends Model {

    /**
     * Top customers by revenue for a given year
     * @param int|null $year
     * @param int $limit
     * @return array
     */
    public function topCustomers($year = null, $limit = 5)
    {
        if (!$year) $year = (int)date('Y');

        // order_detail in this schema stores Variant_Id and Price — use od.Price and od.quantity
        $sql = "SELECT u._UserName_Id as id,
                   u.FullName as name,
                   u.Email as email,
                   SUM(od.quantity * od.Price) as total_revenue,
                   COUNT(DISTINCT o.Order_Id) as orders_count
            FROM orders o
            JOIN order_detail od ON o.Order_Id = od.Order_Id
            JOIN users u ON o._UserName_Id = u._UserName_Id
            WHERE YEAR(o.Order_date) = :year AND o.TrangThai = 'delivered'
            GROUP BY u._UserName_Id
            ORDER BY total_revenue DESC
            LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':year', (int)$year, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Top products by quantity sold for a given year
     * @param int|null $year
     * @param int $limit
     * @return array
     */
    public function topProducts($year = null, $limit = 5)
    {
        if (!$year) $year = (int)date('Y');

        // order_detail uses Variant_Id. Join product_variants to map to Product_Id -> products
        $sql = "SELECT p.Product_Id as id,
                   p.Name as name,
                   SUM(od.quantity) as total_sold,
                   SUM(od.quantity * od.Price) as total_revenue
            FROM order_detail od
            JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
            JOIN products p ON pv.Product_Id = p.Product_Id
            JOIN orders o ON od.Order_Id = o.Order_Id
            WHERE YEAR(o.Order_date) = :year AND o.TrangThai = 'delivered'
            GROUP BY p.Product_Id
            ORDER BY total_sold DESC
            LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':year', (int)$year, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Revenue by month for a given year
     * Returns an associative array keyed by month number 1..12
     * @param int|null $year
     * @return array
     */
    public function revenueByMonth($year = null)
    {
        if (!$year) $year = (int)date('Y');

        // Revenue is saved per order_detail.Price * quantity
        $sql = "SELECT MONTH(o.Order_date) as month, IFNULL(SUM(od.quantity * od.Price),0) as revenue
            FROM orders o
            JOIN order_detail od ON od.Order_Id = o.Order_Id
            WHERE YEAR(o.Order_date) = :year AND o.TrangThai = 'delivered'
            GROUP BY MONTH(o.Order_date)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':year' => (int)$year]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // initialize months 1..12 with 0
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = 0.0;
        }

        foreach ($rows as $r) {
            $m = (int)$r['month'];
            $months[$m] = (float)$r['revenue'];
        }

        return $months;
    }

    /**
     * Revenue for the last N weeks (default 12), keyed by week label YYYY-WW
     * @param int $weeks
     * @return array  ['YYYY-WW' => revenue]
     */
    public function revenueByWeek($weeks = 12)
    {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-' . ($weeks - 1) . ' weeks', strtotime($endDate)));

        $sql = "SELECT YEAR(o.Order_date) as yr, WEEK(o.Order_date,1) as wk, IFNULL(SUM(od.quantity * od.Price),0) as revenue
                FROM orders o
                JOIN order_detail od ON od.Order_Id = o.Order_Id
                WHERE o.Order_date BETWEEN :start AND :end AND o.TrangThai = 'delivered'
                GROUP BY yr, wk
                ORDER BY yr ASC, wk ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Build map by week label
        $map = [];
        foreach ($rows as $r) {
            $label = sprintf('%04d-W%02d', (int)$r['yr'], (int)$r['wk']);
            $map[$label] = (float)$r['revenue'];
        }

        // ensure each week exists in results (chronological)
        $results = [];
        $d = new \DateTime($startDate);
        for ($i = 0; $i < $weeks; $i++) {
            $yr = (int)$d->format('o'); // ISO-8601 year
            $wk = (int)$d->format('W');
            $label = sprintf('%04d-W%02d', $yr, $wk);
            $results[$label] = $map[$label] ?? 0.0;
            $d->modify('+7 days');
        }

        return $results;
    }

    /**
     * Top worst-selling products (least quantity sold) for a given year — includes zero-sales products
     * @param int|null $year
     * @param int $limit
     * @return array
     */
    public function topWorstProducts($year = null, $limit = 5)
    {
        if (!$year) $year = (int)date('Y');

        $sql = "SELECT p.Product_Id as id,
                       p.Name as name,
                       IFNULL(SUM(od.quantity),0) as total_sold
                FROM products p
                LEFT JOIN product_variants pv ON pv.Product_Id = p.Product_Id
                LEFT JOIN order_detail od ON od.Variant_Id = pv.Variant_Id
                LEFT JOIN orders o ON od.Order_Id = o.Order_Id AND YEAR(o.Order_date) = :year AND o.TrangThai = 'delivered'
                GROUP BY p.Product_Id
                ORDER BY total_sold ASC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':year', (int)$year, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Revenue for a given ISO week (per day, 7 days)
     * @param int $year
     * @param int $week
     * @return array  [ 'YYYY-MM-DD' => revenue ]
     */
    public function revenueByWeekPerDay($year, $week)
    {
        // compute start and end date of the ISO week
        $dt = new \DateTime();
        $dt->setISODate((int)$year, (int)$week);
        $start = $dt->format('Y-m-d');
        $dt->modify('+6 days');
        $end = $dt->format('Y-m-d');

        $sql = "SELECT DATE(o.Order_date) as day, IFNULL(SUM(od.quantity * od.Price),0) as revenue
                FROM orders o
                JOIN order_detail od ON od.Order_Id = o.Order_Id
                WHERE o.Order_date BETWEEN :start AND :end
                GROUP BY DATE(o.Order_date)
                ORDER BY DATE(o.Order_date) ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $map[$r['day']] = (float)$r['revenue'];
        }

        // build 7-day array
        $res = [];
        $d = new \DateTime($start);
        for ($i = 0; $i < 7; $i++) {
            $key = $d->format('Y-m-d');
            $res[$key] = $map[$key] ?? 0.0;
            $d->modify('+1 day');
        }

        return $res;
    }

    /**
     * Revenue for a given month (per day) for first $days days (default 30)
     * @param int $year
     * @param int $month
     * @param int $days
     * @return array ['YYYY-MM-DD' => revenue]
     */
    public function revenueByMonthDays($year, $month, $days = 30)
    {
        $start = sprintf('%04d-%02d-01', (int)$year, (int)$month);
        $dt = new \DateTime($start);
        $dt->modify('+' . ($days - 1) . ' days');
        $end = $dt->format('Y-m-d');

        $sql = "SELECT DATE(o.Order_date) as day, IFNULL(SUM(od.quantity * od.Price),0) as revenue
                FROM orders o
                JOIN order_detail od ON od.Order_Id = o.Order_Id
                WHERE o.Order_date BETWEEN :start AND :end
                GROUP BY DATE(o.Order_date)
                ORDER BY DATE(o.Order_date) ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $map[$r['day']] = (float)$r['revenue'];
        }

        $res = [];
        $d = new \DateTime($start);
        for ($i = 0; $i < $days; $i++) {
            $key = $d->format('Y-m-d');
            $res[$key] = $map[$key] ?? 0.0;
            $d->modify('+1 day');
        }

        return $res;
    }

    public function revenueByRangePerDay($start, $end)
    {
        $start = trim($start);
        $end = trim($end);
        if ($start === '' || $end === '') { return []; }

        $sql = "SELECT DATE(o.Order_date) as day, IFNULL(SUM(od.quantity * od.Price),0) as revenue
                FROM orders o
                JOIN order_detail od ON od.Order_Id = o.Order_Id
                WHERE o.Order_date BETWEEN :start AND :end AND o.TrangThai = 'delivered'
                GROUP BY DATE(o.Order_date)
                ORDER BY DATE(o.Order_date) ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $r) { $map[$r['day']] = (float)$r['revenue']; }

        $res = [];
        $d = new \DateTime($start);
        $endDt = new \DateTime($end);
        while ($d <= $endDt) {
            $key = $d->format('Y-m-d');
            $res[$key] = $map[$key] ?? 0.0;
            $d->modify('+1 day');
        }

        return $res;
    }

    /**
     * Top products for an arbitrary date range (start/end inclusive)
     * @param string $start 'YYYY-MM-DD'
     * @param string $end 'YYYY-MM-DD'
     * @param int $limit
     * @return array
     */
    public function topProductsByRange($start, $end, $limit = 5)
    {
        $sql = "SELECT p.Product_Id as id, p.Name as name,
                       SUM(od.quantity) as total_sold,
                       SUM(od.quantity * od.Price) as total_revenue
                FROM order_detail od
                JOIN orders o ON od.Order_Id = o.Order_Id
                JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
                JOIN products p ON pv.Product_Id = p.Product_Id
                WHERE o.Order_date BETWEEN :start AND :end AND o.TrangThai = 'delivered'
                GROUP BY p.Product_Id
                ORDER BY total_sold DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':start', $start);
        $stmt->bindValue(':end', $end);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Top customers for an arbitrary date range (start/end inclusive)
     * @param string $start
     * @param string $end
     * @param int $limit
     * @return array
     */
    public function topCustomersByRange($start, $end, $limit = 5)
    {
        $sql = "SELECT u._UserName_Id as id, u.FullName as name, u.Email as email,
                       SUM(od.quantity * od.Price) as total_revenue,
                       COUNT(DISTINCT o.Order_Id) as orders_count
                FROM orders o
                JOIN order_detail od ON od.Order_Id = o.Order_Id
                JOIN users u ON o._UserName_Id = u._UserName_Id
                WHERE o.Order_date BETWEEN :start AND :end AND o.TrangThai = 'delivered'
                GROUP BY u._UserName_Id
                ORDER BY total_revenue DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':start', $start);
        $stmt->bindValue(':end', $end);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
