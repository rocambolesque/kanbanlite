App.CardsController = Ember.ArrayController.extend({
    actions: {
        search: function() {
            var data = {
                 title: this.get('title'),
                 created_at_from: this.get('created_at_from'),
                 created_at_to: this.get('created_at_to')
            };
            var self = this; // ugh..
            $.ajax({
                url: 'card/search',
                data: data,
                type: 'GET',
                success: function (data) {
                   self.set('cards', data.cards);
                },
                error: function(data) {
                    alert('Search error');
                    console.log(data);
                }
            });
        }
    }
});
