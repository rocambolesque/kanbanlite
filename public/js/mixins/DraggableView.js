App.DraggableViewMixin = Ember.Mixin.create({
    classNames: ['draggable'],
    classNameBindings: ['isDragging'],
    attributeBindings: ['draggable'],
    draggable: 'true', // must be the string 'true'

    dragStart: function(event) {
        this.set('isDragging', true);
        var dragData = { elementId: this.get('elementId') };
        event.dataTransfer.setData(
          'application/json', // first argument is data type
          JSON.stringify( dragData ) // can only transfer strings
        );
    },

    dragEnd: function() {
        this.set('isDragging', false);
    },

    // our custom method,
    // not an html5 drag-n-drop api or ember view event method
    wasDroppedOn: function(droppedOnView) {
        this.set('wasDropped', true);
        this.set('droppedViewName', droppedOnView.get('name'));
    },

    isDraggable: true
});
