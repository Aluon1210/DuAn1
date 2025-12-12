<?php
namespace Core;

class EmailSender
{
    private static function log($text)
    {
        try {
            $logDir = ROOT_PATH . '/storage';
            if (!is_dir($logDir)) { @mkdir($logDir, 0755, true); }
            @file_put_contents($logDir . '/email_smtp.log', '[' . date('Y-m-d H:i:s') . "] " . $text . "\n", FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {}
    }

    private static function read($fp)
    {
        $data = '';
        while ($str = fgets($fp, 515)) {
            $data .= $str;
            if (isset($str[3]) && $str[3] === ' ') {
                break;
            }
        }
        self::log('S: ' . trim($data));
        return $data;
    }

    private static function sendCmd($fp, $cmd)
    {
        self::log('C: ' . trim($cmd));
        fwrite($fp, $cmd);
        return self::read($fp);
    }

    public static function send($to, $subject, $body, $fromEmail = null, $fromName = null)
    {
        $config = require ROOT_PATH . '/src/Config/email.php';
        $smtp = $config['smtp'] ?? [];
        if (empty($smtp['enabled'])) {
            return false;
        }
        $host = $smtp['host'] ?? '';
        $port = (int)($smtp['port'] ?? 465);
        $username = $smtp['username'] ?? '';
        $password = $smtp['password'] ?? '';
        $encryption = strtolower($smtp['encryption'] ?? 'ssl');
        $timeout = (int)($smtp['timeout'] ?? 15);
        $fromEmail = $fromEmail ?: ($smtp['from_email'] ?? $username);
        $fromName = $fromName ?: ($smtp['from_name'] ?? '');

        if ($host === '' || $username === '' || $password === '' || $fromEmail === '' || $to === '') {
            return false;
        }

        $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
        $fp = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        if (!$fp && $encryption === 'tls') {
            $remote = $host . ':' . $port;
            $fp = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        }
        if (!$fp) {
            self::log('Connect failed: ' . $errno . ' ' . $errstr);
            return false;
        }
        stream_set_timeout($fp, $timeout);

        $res = self::read($fp);
        if (strpos($res, '220') !== 0) {
            fclose($fp);
            return false;
        }

        $hostName = 'localhost';
        $res = self::sendCmd($fp, "EHLO {$hostName}\r\n");
        if (strpos($res, '250') !== 0) {
            $res = self::sendCmd($fp, "HELO {$hostName}\r\n");
            if (strpos($res, '250') !== 0) {
                fclose($fp);
                return false;
            }
        }
        if ($encryption === 'tls' && stripos($res, 'STARTTLS') !== false) {
            $res = self::sendCmd($fp, "STARTTLS\r\n");
            if (strpos($res, '220') !== 0) {
                fclose($fp);
                return false;
            }
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($fp);
                return false;
            }
            $res = self::sendCmd($fp, "EHLO {$hostName}\r\n");
            if (strpos($res, '250') !== 0) {
                fclose($fp);
                return false;
            }
        }

        $res = self::sendCmd($fp, "AUTH LOGIN\r\n");
        if (strpos($res, '334') !== 0) {
            fclose($fp);
            return false;
        }
        $res = self::sendCmd($fp, base64_encode($username) . "\r\n");
        if (strpos($res, '334') !== 0) {
            fclose($fp);
            return false;
        }
        $res = self::sendCmd($fp, base64_encode($password) . "\r\n");
        if (strpos($res, '235') !== 0) {
            self::log('Auth failed');
            fclose($fp);
            return false;
        }

        $res = self::sendCmd($fp, "MAIL FROM:<{$fromEmail}>\r\n");
        if (strpos($res, '250') !== 0) {
            fclose($fp);
            return false;
        }

        $res = self::sendCmd($fp, "RCPT TO:<{$to}>\r\n");
        if (strpos($res, '250') !== 0 && strpos($res, '251') !== 0) {
            fclose($fp);
            return false;
        }

        $res = self::sendCmd($fp, "DATA\r\n");
        if (strpos($res, '354') !== 0) {
            fclose($fp);
            return false;
        }

        $headers = [];
        $headers[] = "From: " . ($fromName ? "{$fromName} <{$fromEmail}>" : "<{$fromEmail}>");
        $headers[] = "To: <{$to}>";
        $headers[] = "Subject: " . $subject;
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
        $headers[] = "Content-Transfer-Encoding: 8bit";
        $safeBody = preg_replace("/^\./m", "..", $body);
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $safeBody . "\r\n.\r\n";
        fwrite($fp, $message);
        $res = self::read($fp);
        if (strpos($res, '250') !== 0) {
            fclose($fp);
            return false;
        }
        self::sendCmd($fp, "QUIT\r\n");
        fclose($fp);
        return true;
    }

    public static function sendCustom($to, $subject, $body, array $opts)
    {
        $host = $opts['host'] ?? '';
        $port = (int)($opts['port'] ?? 465);
        $username = $opts['username'] ?? '';
        $password = $opts['password'] ?? '';
        $encryption = strtolower($opts['encryption'] ?? 'ssl');
        $timeout = (int)($opts['timeout'] ?? 30);
        $fromEmail = $opts['from_email'] ?? $username;
        $fromName = $opts['from_name'] ?? '';

        if ($host === '' || $username === '' || $password === '' || $fromEmail === '' || $to === '') {
            return false;
        }

        $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
        $fp = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        if (!$fp && $encryption === 'tls') {
            $remote = $host . ':' . $port;
            $fp = @stream_socket_client($remote, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        }
        if (!$fp) {
            self::log('Connect failed custom: ' . $errno . ' ' . $errstr);
            return false;
        }
        stream_set_timeout($fp, $timeout);

        $res = self::read($fp);
        if (strpos($res, '220') !== 0) { fclose($fp); return false; }
        $hostName = 'localhost';
        $res = self::sendCmd($fp, "EHLO {$hostName}\r\n");
        if (strpos($res, '250') !== 0) {
            $res = self::sendCmd($fp, "HELO {$hostName}\r\n");
            if (strpos($res, '250') !== 0) { fclose($fp); return false; }
        }
        if ($encryption === 'tls' && stripos($res, 'STARTTLS') !== false) {
            $res = self::sendCmd($fp, "STARTTLS\r\n");
            if (strpos($res, '220') !== 0) { fclose($fp); return false; }
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) { fclose($fp); return false; }
            $res = self::sendCmd($fp, "EHLO {$hostName}\r\n");
            if (strpos($res, '250') !== 0) { fclose($fp); return false; }
        }
        $res = self::sendCmd($fp, "AUTH LOGIN\r\n");
        if (strpos($res, '334') !== 0) { fclose($fp); return false; }
        $res = self::sendCmd($fp, base64_encode($username) . "\r\n");
        if (strpos($res, '334') !== 0) { fclose($fp); return false; }
        $res = self::sendCmd($fp, base64_encode($password) . "\r\n");
        if (strpos($res, '235') !== 0) { fclose($fp); return false; }
        $res = self::sendCmd($fp, "MAIL FROM:<{$fromEmail}>\r\n");
        if (strpos($res, '250') !== 0) { fclose($fp); return false; }
        $res = self::sendCmd($fp, "RCPT TO:<{$to}>\r\n");
        if (strpos($res, '250') !== 0 && strpos($res, '251') !== 0) { fclose($fp); return false; }
        $res = self::sendCmd($fp, "DATA\r\n");
        if (strpos($res, '354') !== 0) { fclose($fp); return false; }
        $headers = [];
        $headers[] = "From: " . ($fromName ? "{$fromName} <{$fromEmail}>" : "<{$fromEmail}>");
        $headers[] = "To: <{$to}>";
        $headers[] = "Subject: " . $subject;
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
        $headers[] = "Content-Transfer-Encoding: 8bit";
        $safeBody = preg_replace("/^\./m", "..", $body);
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $safeBody . "\r\n.\r\n";
        fwrite($fp, $message);
        $res = self::read($fp);
        if (strpos($res, '250') !== 0) { fclose($fp); return false; }
        self::sendCmd($fp, "QUIT\r\n");
        fclose($fp);
        return true;
    }
}
