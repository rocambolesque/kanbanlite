App.NewController = Ember.ArrayController.extend({
    actions: {
        save: function() {
            var data = {
                card: {
                    title: this.get('title'),
                    description: this.get('description'),
                    status: this.get('selectedStatus'),
                    owner: this.get('selectedOwner')
                }
            };
            var self = this; // ugh..
            $.ajax({
                url: 'card',
                data: data,
                type: 'POST',
                success: function (data) {
                    alert('Card saved');
                    self.transitionToRoute('cards');
                },
                error: function(data) {
                    alert('Error, card not saved');
                    console.log(data);
                }
            });
        }
    }
});

