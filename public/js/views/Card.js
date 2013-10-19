App.CardView = Ember.View.extend({
    templateName: '_card',
    classNames: ['draggable'],
    classNameBindings: ['isDragging'],
    attributeBindings: ['draggable'],
    draggable: 'true', // must be the string 'true'

    dragStart: function(event) {
        this.set('isDragging', true);
    },
    dragEnd: function() {
        this.set('isDragging', false);
    }
});
