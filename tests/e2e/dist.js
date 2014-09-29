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


    it("set default values for plugin", function () {

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('new-user-default-credits')).sendKeys('10');

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css("#setting-error-settings_updated")).getText()).toBe('Settings saved.');

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

        expect(element(by.css("#setting-error-settings_updated")).getText()).toBe('Settings saved.');

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

        expect(element(by.css("#setting-error-settings_updated")).getText()).toBe('Settings saved.');

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


    it("set value for existing post helloworld to 4", function() {

        browser.get('/wp-admin/post.php?post=1&action=edit');
        browser.sleep( 2000 );

        element(by.id('post-type-value')).clear();

        element(by.id('post-type-value')).sendKeys('4');

        element(by.id('publish')).click();
        browser.sleep( 2000 );

        expect(element(by.id('message')).getText()).toMatch('Post updated. View post');

        expect(element(by.id('post-type-value')).getAttribute('value')).toBe('4');

    });


    it('validating options required', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(true);

    });


    it('validating options numeric', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('new-user-default-credits')).sendKeys('aaa');

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(true);

    });


    it('saving new options', function(){

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        element(by.id('new-user-default-credits')).clear();

        element(by.id('new-user-default-credits')).sendKeys('150');

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(false);

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

        element(by.id('new-user-default-credits')).clear();

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css('label[for="new-user-default-credits"]')).isPresent()).toBe(true);

        element(by.id('add-post-type-value')).click();

        expect(element(by.css('label[for="new-post-type-value-name"]')).isPresent()).toBe(true);

    });

    it("administrator set user credits", function() {

        login_steps();

        browser.get('/wp-admin/user-edit.php?user_id=1');
        browser.sleep( 2000 );

        element(by.id('credits-coins-user-credits')).clear();

        element(by.id('credits-coins-user-credits')).sendKeys('150');

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.id('message')).getText()).toBe('Profile updated.');

        expect(element(by.id('credits-coins-user-credits')).getAttribute('value')).toBe('150');

    });


    it("user not logged accessing content hello world post protected by credits, anonymous user cannot access it", function() {

        login_steps();

        logout_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        expect(element(by.id('btn-buy-post')).isPresent()).toBe(true);

        expect(element(by.css('body')).getText()).not.toMatch('.*Welcome to WordPress.*');

    });


    it("user not logged try to buy a post", function() {

        login_steps();

        logout_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        element(by.id('btn-buy-post')).click();
        browser.sleep( 2000 );

        expect(element(by.css('.alert.alert-danger')).getText()).toBe('Ops ... To buy a post before do login. Thanks!');

    });


    it("user logged accessing content hello world post, user has not bought the post and cannot access", function() {

        login_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        expect(element(by.id('btn-buy-post')).isPresent()).toBe(true);

        expect(element(by.css('body')).getText()).not.toMatch('.*Welcome to WordPress.*');

    });


    it("user logged buy hello world post", function() {

        login_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        expect(element(by.id('btn-buy-post')).isPresent()).toBe(true);

        expect(element(by.css('body')).getText()).not.toMatch('.*Welcome to WordPress.*');

        element(by.id('btn-buy-post')).click();
        browser.sleep( 2000 );

        expect(element(by.css('.alert.alert-success')).getText()).toMatch('.*Good! Your order has been completed successfully.*');

    });


    it("user logged accessing content hello world post", function() {

        login_steps();

        browser.get('/index.php?p=1');
        browser.sleep( 2000 );

        expect(element(by.id('btn-buy-post')).isPresent()).toBe(false);

        expect(element(by.css('body')).getText()).toMatch('.*Welcome to WordPress.*');

    });


    it("check registration user movement: buying post hello world", function() {

        login_steps();

        browser.get('/wp-admin/user-edit.php?user_id=1');
        browser.sleep( 2000 );

        element(by.id('btn-visualizza-movimenti')).click();
        browser.sleep( 2000 );

        expect(element(by.css('#wrapper-latest-recharges tr:nth-child(2)')).getText()).toMatch('2 maronl_admin maronl_admin (.*) -4 credits-co buy post 1');

    });


    it("check registration user movement: credit recharge using admin panel", function() {

        login_steps();

        browser.get('/wp-admin/user-edit.php?user_id=1');
        browser.sleep( 2000 );

        element(by.id('btn-visualizza-movimenti')).click();
        browser.sleep( 2000 );

        expect(element(by.css('#wrapper-latest-recharges tr:nth-child(3)')).getText()).toMatch('1 maronl_admin maronl_admin (.*) 150 wp-admin defined new credits payoff using wp-admin');

    });


    it("remove default credits for post", function () {

        login_steps();

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        expect(element(by.css('a.remove-post-type-value[href="#post,4"]')).isPresent()).toBe(true);

        element(by.css('a.remove-post-type-value[href="#post,4"]')).click();

        expect(element(by.css('a.remove-post-type-value[href="#post,4"]')).isPresent()).toBe(false);

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css("#setting-error-settings_updated")).getText()).toBe('Settings saved.');

    });


    it("remove default credits for page", function () {

        login_steps();

        browser.get('/wp-admin/options-general.php?page=credits-coins-plugin-options');
        browser.sleep( 2000 );

        expect(element(by.css('a.remove-post-type-value[href="#page,6"]')).isPresent()).toBe(true);

        element(by.css('a.remove-post-type-value[href="#page,6"]')).click();

        expect(element(by.css('a.remove-post-type-value[href="#page,6"]')).isPresent()).toBe(false);

        element(by.id('submit')).click();
        browser.sleep( 2000 );

        expect(element(by.css("#setting-error-settings_updated")).getText()).toBe('Settings saved.');

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