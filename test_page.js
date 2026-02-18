const puppeteer = require('puppeteer-core');

(async () => {
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    
    // 启用控制台日志
    page.on('console', msg => console.log('PAGE LOG:', msg.text()));
    page.on('pageerror', err => console.log('PAGE ERROR:', err.message));
    
    await page.goto('http://localhost:5267/page/OrderCustomer?lang=en&InvoiceId=1', {
        waitUntil: 'networkidle0',
        timeout: 30000
    });
    
    // 等待数据加载
    await new Promise(r => setTimeout(r, 5000));
    
    // 检查表格内容
    const tableContent = await page.$eval('#invoice_lines_section-body', el => el.innerHTML);
    console.log('TABLE CONTENT:', tableContent.substring(0, 500));
    
    await browser.close();
})();
