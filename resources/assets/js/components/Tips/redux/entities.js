const types = {
    ADD_ENTITIES: 'ADD_ENTITIES',
    UPDATE_ENTITY: 'UPDATE_ENTITY',
    ADD_COUPLED_STATISTIC_TO_TIP: 'ADD_COUPLED_STATISTIC_TO_TIP',
    DECOUPLE_STATISTIC_FROM_TIP: 'DECOUPLE_STATISTIC_FROM_TIP',
    REMOVE_TIP: 'REMOVE_TIP',
    STATISTIC_DELETED: 'STATISTIC_DELETED',
};

const actions = {
    addEntities: entities => ({type: types.ADD_ENTITIES, entities}),
    updateEntity: (name, key, entity) => ({type: types.UPDATE_ENTITY, name, key, entity}),
    addCoupledStatisticToTip: (coupledStatisticId, tipId) => ({
        type: types.ADD_COUPLED_STATISTIC_TO_TIP,
        coupledStatisticId,
        tipId
    }),
    decoupleStatisticFromTip: (coupledStatistic) => ({type: types.DECOUPLE_STATISTIC_FROM_TIP, coupledStatistic}),
    removeTip: id => ({type: types.REMOVE_TIP, id}),
    removeStatistic: id => ({type: types.STATISTIC_DELETED, id}),
};

const defaultState = {
    tips: {},
    coupledStatistics: {},
    cohorts: {},
    statistics: {},
    statisticVariables: {},
    educationProgramTypes: {},
};

const reducer = (state = defaultState, action) => {

    switch (action.type) {

        case types.ADD_ENTITIES: {
            const newEntities = {...state};
            Object.keys(action.entities).forEach(entityKey => newEntities[entityKey] = {...state[entityKey], ...action.entities[entityKey]});
            return newEntities;
        }
        case types.UPDATE_ENTITY: {
            return {...state, [action.name]: {...state[action.name], [action.key]: action.entity}};
        }

        case types.ADD_COUPLED_STATISTIC_TO_TIP: {
            const tip = {...state.tips[action.tipId]};
            tip.coupled_statistics = [...tip.coupled_statistics, action.coupledStatisticId];
            return {
                ...state,
                tips: {...state.tips, [tip.id]: tip}
            };
        }

        case types.DECOUPLE_STATISTIC_FROM_TIP: {
            const tip = {...state.tips[action.coupledStatistic.tip_id]};
            tip.coupled_statistics = [...tip.coupled_statistics];
            tip.coupled_statistics.splice(tip.coupled_statistics.indexOf(action.coupledStatistic.id), 1);

            const coupledStatistics = {...state.coupledStatistics};
            delete coupledStatistics[action.coupledStatistic.id];

            return {
                ...state,
                tips: {...state.tips, [tip.id]: tip},
                coupledStatistics
            }
        }

        case types.REMOVE_TIP: {
            const tips = {...state.tips};
            delete tips[action.id];
            return {...state, tips};
        }

        case types.STATISTIC_DELETED: {
            const statistics = {...state.statistics};
            delete statistics[action.id];
            return {...state, statistics};
        }

    }

    return state;
};


export {
    types, actions, reducer
}