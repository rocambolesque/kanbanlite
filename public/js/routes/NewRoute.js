App.NewRoute = Ember.Route.extend({
    setupController: function(controller, model) {
        this.store.find('status').then(function(statuses){
            controller.set('statuses', statuses)
            controller.set('selectedStatus', statuses.get('content')[0].get('id'));
        });
        this.store.find('owner').then(function(owners){
            controller.set('owners', owners);
            controller.set('selectedOwner', owners.get('content')[0].get('id'));
        });
    }
});
