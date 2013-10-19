App.CardsRoute = Ember.Route.extend({
    setupController: function(controller, model) {
        this.get('store').find('status').then(function(statuses) {
            controller.set('statuses', statuses);
            controller.set('todo', statuses.get('content')[0].get('id'));
            controller.set('doing', statuses.get('content')[1].get('id'));
            controller.set('done', statuses.get('content')[2].get('id'));
        });

        this.get('store').find('card', {status: 1}).then(function(cards){
            controller.set('todoCards', cards);
        });

        this.get('store').find('card', {status: 2}).then(function(cards){
            controller.set('doingCards', cards);
        });

        this.get('store').find('card', {status: 3}).then(function(cards){
            controller.set('doneCards', cards);
        });
    }
});
