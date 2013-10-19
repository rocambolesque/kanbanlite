App = Ember.Application.create();

App.Router.map(function() {
    this.route("index", { path: "/" });
    this.route("new", { path: "/new" });
    this.resource('cards');
    this.resource('card', { path: '/card/:id' });
});

// no plurals
DS.RESTAdapter.reopen({
    pathForType: function(type) {
        return type;
    }
});
