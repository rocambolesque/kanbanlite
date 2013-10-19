App.CardRoute = Ember.Route.extend({
    model: function(params) {
        return this.get('store').find('card', params.id);
    },
    setupController: function(controller, model) {
        controller.set('model', model);
        this.store.find('status').then(function(statuses){
            controller.set('statuses', statuses)
            controller.set('selectedStatus', model.get('status').get('id'));
        });
        this.store.find('owner').then(function(owners){
            controller.set('owners', owners);
            controller.set('selectedOwner', model.get('owner').get('id'));
        });
    }
});
