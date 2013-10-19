App.CardController = Ember.ObjectController.extend({
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
                url: 'card/'+this.get('id'),
                data: data,
                type: 'PUT',
                success: function (data) {
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
