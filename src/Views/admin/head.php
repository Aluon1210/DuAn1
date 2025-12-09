<style>
/* ===================== BASE STYLES ===================== */
html, body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', Helvetica, Arial, sans-serif;
    background: #f8f9fa;
    color: #333;
    line-height: 1.6;
}

h1,h2,h3,h4,h5,h6 {
    font-family: 'Playfair Display', serif;
    color: #3b3024;
}

a { text-decoration: none; color: inherit; }

/* ===================== HEADER ===================== */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100px; /* chiều cao header */
    background: #1B2127;
    color: #FFD700;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 80px; /* khoảng cách lề */
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    /* box-shadow: 0 8px 25px rgba(0,0,0,0.5); */
    z-index: 1000;
}

.header .brand {
    display: flex;
    align-items: center;
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
}

.header .brand i {
    margin-right: 12px;
    font-size: 34px;
}

.header nav {
    display: flex;
    align-items: center;
    gap: 25px;
    padding-right: 200px; /* cách mép phải 50px */
}

.header nav a {
    color: #fff;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 10px;
    border: 1px solid rgba(255,215,0,0.4);
    box-shadow: 0 3px 8px rgba(0,0,0,0.25);
    transition: all 0.2s ease;
}

.header nav a:hover {
    background: rgba(255,215,0,0.1);
    transform: translateY(-2px);
}

/* ===================== SIDEBAR ===================== */
aside.sidebar {
    position: fixed;
    top: 100px; /* bằng height header */
    left: 0;
    width: 260px;
    height: calc(100vh - 100px); /* phần còn lại */
    background: #fff;
    border-right: 3px solid #CD853F;
    padding: 20px;
    overflow-y: auto;
    box-shadow: 4px 0 15px rgba(0,0,0,0.08);
    z-index: 999;
}

aside.sidebar::-webkit-scrollbar {
    width: 6px;
}

aside.sidebar::-webkit-scrollbar-track {
    background: #fff;
    border-radius: 10px;
}

aside.sidebar::-webkit-scrollbar-thumb {
    background: rgba(205,133,63,0.4);
    border-radius: 10px;
}

.menu-item {
    display: block;
    padding: 14px 16px;
    margin-bottom: 10px;
    background: #e8e8e8;
    color: #000;
    font-weight: 500;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.menu-item:hover {
    background: #dcdcdc;
    transform: translateX(6px) scale(1.02);
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

.menu-item.active {
    background: #fffacd;
    color: #3b3024;
    font-weight: 600;
    border: 2px solid #cd853f;
    box-shadow: 0 4px 12px rgba(205,133,63,0.35);
}

/* ===================== MAIN CONTENT ===================== */
main.admin-content {
    margin-top: 100px; /* bằng height header */
    margin-left: calc(260px + 50px); /* sidebar + khoảng cách 50px */
    width: calc(100% - 260px - 50px);
    padding: 30px;
    box-sizing: border-box;
}

/* ===================== RESPONSIVE ===================== */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        height: auto;
        padding: 15px 30px;
    }

    aside.sidebar {
        position: relative;
        top: 0;
        width: 100%;
        height: auto;
    }

    main.admin-content {
        margin-left: 0;
        margin-top: calc(100px + 20px); /* header cao hơn khi responsive */
        width: 100%;
        padding: 20px;
    }
}
</style>
