// test work in progess. once completed they will be moved to the dist.js
describe('Hello World form', function() {

    beforeEach(function() {
        return browser.ignoreSynchronization = true;
    });




});


function login_steps() {
    browser.get('/wp-login');

    element(by.id('user_login')).clear();

    element(by.id('user_login')).sendKeys('maronl_admin');

    element(by.id('user_pass')).clear();

    element(by.id('user_pass')).sendKeys('maronl');

    element(by.id('wp-submit')).click();
    browser.sleep( 2000 );
}

function logout_steps() {
    browser.get('/wp-admin');
    browser.sleep( 2000 );

    browser.actions().mouseMove(element(by.id('wp-admin-bar-my-account'))).perform();
    browser.sleep( 2000 );

    element(by.id('wp-admin-bar-logout')).click();
    browser.sleep( 2000 );
}