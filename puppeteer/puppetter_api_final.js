const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
  const browser = await puppeteer.launch({args: ['--no-sandbox']});
  Object.defineProperty(browser, "languages", {
    get: function() {
      return [lang];
    }
  });
  const url = process.argv[2];// https://www.buscape.com.br
  const lang = 'pt-BR';
  const ua = 'Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36';
  const page = await browser.newPage();
  await page.setUserAgent(ua);
  response = await page.goto(url);
  let retorno = {
      ip_adress: response.remoteAddress().ip,
      url: response.url,
      port: response.remoteAddress().port,
      status: response.status(),
      headers: response.headers(),
      response: await page.content(),
  };

  let data = JSON.stringify(retorno, null, 2);
  return data;
  await browser.close();
})();

async function autoScroll(page){
    await page.evaluate(async () => {
        await new Promise((resolve, reject) => {
            var totalHeight = 0;
            var distance = 5;
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

