// e2e tests to be executed to validate dist version
describe('Hello World form', function() {

    beforeEach(function() {
        return browser.ignoreSynchronization = true;
    });

    it("user success login", function () {

        login_steps();

        expect(element(by.id("login_error")).isPresent()).toBe(false);

    });

    it("new settings submenu is present", function () {

        browser.get('/wp-admin/options-general.php');

        browser.sleep( 2000 );

        expect(element(by.css('[href="options-general.php?page=credits-coins-plugin-options"]')).isPresent()).toBe(true);

    });


    it("new settings submenu is present", function () {

        browser.get('/wp-admin/options-general.php');

        browser.sleep( 2000 );

        expect(element(by.css('[href="options-general.php?page=credits-coins-plugin-options"]')).isPresent()).toBe(true);

    });


    it("set default credits for post", function () {

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-post-type-value-name')).click();

        element(by.css('option[value="post"]')).click();

        element(by.id('new-post-type-value-credit')).clear();

        element(by.id('new-post-type-value-credit')).sendKeys('4');

        element(by.id('add-post-type-value')).click();
        browser.sleep( 1000 );

        expect(element(by.css('li[data-value="post,4"]')).isPresent()).toBe(true);

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css("#setting-error-settings_updated p strong")).getText()).toBe('Settings saved.');

        expect(element(by.css('li[data-value="post,4"]')).isPresent()).toBe(true);

    });

    it("set default credits for page", function () {

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-post-type-value-name')).click();

        element(by.css('option[value="page"]')).click();

        element(by.id('new-post-type-value-credit')).clear();

        element(by.id('new-post-type-value-credit')).sendKeys('6');

        element(by.id('add-post-type-value')).click();
        browser.sleep( 1000 );

        expect(element(by.css('li[data-value="page,6"]')).isPresent()).toBe(true);

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css("#setting-error-settings_updated p strong")).getText()).toBe('Settings saved.');

        expect(element(by.css('li[data-value="page,6"]')).isPresent()).toBe(true);

    });


    it("value for existing post helloworld should be zero", function() {

        browser.get('/wp-admin/post.php?post=1&action=edit');
        browser.sleep( 2000 );

        expect(element(by.id('post-type-value')).getAttribute('value')).toBe('0');

    });


    it("value for new post shoule be 4", function() {

        browser.get('/wp-admin/post-new.php');
        browser.sleep( 2000 );

        expect(element(by.id('post-type-value')).getAttribute('value')).toBe('4');

    });


    it("save new post and check credits assigned", function() {

        browser.get('/wp-admin/post-new.php');
        browser.sleep( 2000 );

        element(by.id('title')).clear();

        element(by.id('title')).sendKeys('saving new post to test credits assigned by default are saved properly');

        element(by.id('publish')).click();
        browser.sleep( 2000 );

        expect(element(by.id('message')).getText()).toMatch('Post published. View post');

        expect(element(by.id('post-type-value')).getAttribute('value')).toBe('4');

        element(by.id('post-type-value')).clear();

        element(by.id('post-type-value')).sendKeys('12');

        element(by.id('publish')).click();
        browser.sleep( 2000 );

        expect(element(by.id('message')).getText()).toMatch('Post updated. View post');

        expect(element(by.id('post-type-value')).getAttribute('value')).toBe('12');

    });


    it('validating options required', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('single-credit-value')).clear();

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(true);

        expect(element(by.css('label[for="single-credit-value"]')).isPresent()).toBe(true);

    });


    it('validating options numeric', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('new-user-default-credits')).sendKeys('aaa');

        element(by.id('single-credit-value')).clear();

        element(by.id('single-credit-value')).sendKeys('bbb');

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(true);

        expect(element(by.css('label[for="single-credit-value"]')).isPresent()).toBe(true);

    });


    it('saving new options', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('new-user-default-credits')).sendKeys('150');

        element(by.id('single-credit-value')).clear();

        element(by.id('single-credit-value')).sendKeys('4');

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(false);

        expect(element(by.css('label[for="single-credit-value"]')).isPresent()).toBe(false);

        expect(element(by.id('setting-error-settings_updated')).getText()).toMatch('Settings saved.');

    });


    it('options validation also after trial to add new "Post Types with Credits" value', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('add-post-type-value')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-post-type-value-name"]')).isPresent()).toBe(true);

        expect(element(by.css('label[for="new-post-type-value-credit"]')).isPresent()).toBe(true);

        element(by.id('new-post-type-value-credit')).clear();

        element(by.id('new-post-type-value-credit')).sendKeys('aaa');

        element(by.id('add-post-type-value')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-post-type-value-credit"]')).isPresent()).toBe(true);

        element(by.id('single-credit-value')).clear();

        element(by.id('new-user-default-credits')).clear();

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(true);

        expect(element(by.css('label[for="single-credit-value"]')).isPresent()).toBe(true);

        element(by.id('add-post-type-value')).click();

        expect(element(by.css('label[for="new-post-type-value-name"]')).isPresent()).toBe(true);

        expect(element(by.css('label[for="new-post-type-value-credit"]')).isPresent()).toBe(true);

    });


    it("user not logged accessing content hello world post protected by credits, anonymous user cannot access it", function() {

        login_steps();

        logout_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        expect(element(by.id('btn-buy-post')).isPresent()).toBe(true);

        expect(element(by.css('body')).getText()).not.toMatch('.*Welcome to WordPress.*');

    });


    it("user logged accessing content hello world post, user has not bought the post and cannot access", function() {

        login_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        expect(element(by.id('btn-buy-post')).isPresent()).toBe(true);

        expect(element(by.css('body')).getText()).not.toMatch('.*Welcome to WordPress.*');

    });





    it("user logout", function() {

        login_steps();

        logout_steps();

        expect(element(by.css(".message")).getText()).toBe('You are now logged out.');

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