exports.config = {
    seleniumServerJar: 'selenium/selenium-server-standalone-2.42.2.jar',
specs: [
    'e2e/dist.js'
],
seleniumArgs: ['-browserTimeout=60'],
capabilities: {
'browserName': 'chrome'
},
baseUrl: 'http://www.wp-plugin-dev.luca/',
allScriptsTimeout: 30000
};