App.DroppableViewMixin = Ember.Mixin.create({
    classNames: ['droppable'],
    classNameBindings: ['isDragOver'],
    isDroppable: true,

    dragEnter: function() {
        this.set('isDragOver', true);
    },

    dragLeave: function() {
        this.set('isDragOver', false);
    },

    dragOver: function(event) {
        this.set('isDragOver', true);
        event.preventDefault();
    },

    drop: function(event) {
        var dragData = JSON.parse( event.dataTransfer.getData('application/json') );
        var draggedView = Ember.View.views[ dragData.elementId ];
        draggedView.wasDroppedOn(this);

        this.todoCards = this.get('controller').get('todoCards').get('content');
        this.doingCards = this.get('controller').get('doingCards').get('content');
        this.doneCards = this.get('controller').get('doneCards').get('content');

        // target status is hidden inside status zone
        var statusId = $(event.currentTarget.parentNode).children('.card-status').val();
        var status = this.getStatus(statusId);
        if (status === null) {
            console.log('could not find status #'+statusId);
            return;
        }

        // card id is in the first child of the card template
        var cardId = draggedView._childViews[0].get('value');
        var card = this.getCard(cardId);
        if (card === null) {
            console.log('could not find card #'+cardId);
            return;
        }

        var formerStatus = card.get('status');
        card.set('status', status);

        var data = {
            card: {
                title: card.get('title'),
                description: card.get('description'),
                status: status.get('id'),
                owner: card._data.owner.get('id')
            }
        };

        var self = this;
        $.ajax({
            url: 'card/'+card.get('id'),
            data: data,
            type: 'PUT',
            success: function (data) {
                if (statusId == 1) {
                    self.get('controller').get('todoCards').pushObject(card);
                } else if (statusId == 2) {
                    self.get('controller').get('doingCards').pushObject(card);
                } else if (statusId == 3) {
                    self.get('controller').get('doneCards').pushObject(card);
                }

                if (formerStatus.get('id') == 1) {
                    var c = self.findCard(self.todoCards, card.get('id'));
                    if (c !== null) {
                        self.get('controller').get('todoCards').removeObject(c);
                    }
                } else if (formerStatus.get('id') == 2) {
                    var c = self.findCard(self.doingCards, card.get('id'));
                    if (c !== null) {
                        self.get('controller').get('doingCards').removeObject(c);
                    }
                } else if (formerStatus.get('id') == 3) {
                    var c = self.findCard(self.doneCards, card.get('id'));
                    if (c !== null) {
                        self.get('controller').get('doneCards').removeObject(c);
                    }
                }
            },
            error: function(data) {
                alert('Error, card not saved');
                console.log(data);
            }
        });

        this.set('isDragOver', false);
        this.set('didReceiveDrop', true);
        this.set('droppedElementId', draggedView.get('elementId'));
    },

    getCard: function(id) {
        var card = null;

        card = this.findCard(this.todoCards, id);
        if (card !== null) {
            return card;
        }

        card = this.findCard(this.doingCards, id);
        if (card !== null) {
            return card;
        }

        card = this.findCard(this.doneCards, id);

        return card;

    },

    findCard: function(cards, id) {
        for (var i = 0, l = cards.length ; i < l ; i++) {
            if (cards[i].get('id') == id) {
                return cards[i];
            }
        }
        return null;
    },

    getStatus: function(id) {
        var statuses = this.get('controller').get('statuses').get('content');
        for (var i = 0, l = statuses.length ; i < l ; i++) {
            if (statuses[i].get('id') == id) {
                return statuses[i];
            }
        }
        return null;
    }
});
