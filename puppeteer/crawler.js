const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
  const lang = 'pt-BR';
  const ua = 'Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36';
  // const ua = 'simplex-c4';
  const browser = await puppeteer.launch({args: ['--no-sandbox']});
  Object.defineProperty(browser, "languages", {
    get: function() {
      return [lang];
    }
  });
  try {
    const page = await browser.newPage();
    await page.setRequestInterception(false);
    // const json_headers = '{"Referer": "https://www.google.com/", "User-Agent": "simplex-c4", "Accept-Language": "en-US,en;q=0.5"}';
    const json_headers = process.argv[3];
    const extra_headers = JSON.parse(json_headers);
    await page.setExtraHTTPHeaders(extra_headers);
    const response = await page.goto(process.argv[2],{ waitUntil: 'networkidle0',referer: process.argv[2]});

    await autoScroll(page);
    // console.log(await page.content());
    let retorno = {
        ip_adress: response.remoteAddress().ip,
        url: response.url,
        port: response.remoteAddress().port,
        status: response.status(),
        headers: response.headers(),
        response: await page.content(),
    };

    let data = JSON.stringify(retorno, null, 2);
    // return data;
    console.log(data);
  }  catch (ex) {
    console.error("inner", ex.message);
  }  finally {
    await browser.close();
  }

})();

async function autoScroll(page){
    await page.evaluate(async () => {
        await new Promise((resolve, reject) => {
            var totalHeight = 0;
            var distance = 100;
            var timer = setInterval(() => {
                var scrollHeight = document.body.scrollHeight;
                window.scrollBy(0, distance);
                totalHeight += distance;

                if(totalHeight >= scrollHeight){
                    clearInterval(timer);
                    resolve();
                }
            },50);
        });
    });
}
