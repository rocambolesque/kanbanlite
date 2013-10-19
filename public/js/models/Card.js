App.Card = DS.Model.extend({
    title: DS.attr('string'),
    description: DS.attr('string'),
    status: DS.belongsTo('status'),
    owner: DS.belongsTo('owner'),
    created_at: DS.attr('string')
});
