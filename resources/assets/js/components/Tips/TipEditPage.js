import {connect} from "react-redux";
import React from "react";
import {actions as coupleStatisticActions} from "./redux/coupleStatistic";
import {actions as entityActions} from "./redux/entities";
import {actions as uiActions} from "./redux/tipPageUi";
import axios from "axios/index";
import {Schema} from "../../Schema";
import {normalize} from "normalizr";
import CreateForm from "../Statistics/CreateForm";
import {Link} from "react-router-dom";
import Modal from 'react-responsive-modal';
import Joyride from "react-joyride";
import {ACTIONS, EVENTS} from 'react-joyride/es/constants';


class TipEditPage extends React.Component {

    joyrideRef = null;

    constructor(props) {
        super(props);
        this.state = {
            submitting: false,
            saveButtonText: Lang.get('statistics.couple'),
            showCoupleStatisticModal: false,
            coupleRequestBusy: false,
            showNewStatisticModal: false,
            runJoyride: false,
            stepIndex: 0

        }

    }

    coupleStatistic = () => {
        const {tip, coupleStatisticForm, statistics, storeNewCoupledStatistic} = this.props;
        this.setState({coupleRequestBusy: true});
        axios.post('/api/tip-coupled-statistics', {
            tip_id: tip.id,
            statistic_id: coupleStatisticForm.statistic,
            threshold: coupleStatisticForm.threshold,
            method: statistics[coupleStatisticForm.statistic].hasOwnProperty('method') ? statistics[coupleStatisticForm.statistic].method : null,
            comparisonOperator: coupleStatisticForm.comparisonOperator,

        }).then(response => {
            storeNewCoupledStatistic(normalize(response.data, Schema.coupledStatistic));
            if (this.state.runJoyride) {
                this.joyrideRef.helpers.next();
            }
            this.setState({showCoupleStatisticModal: false, coupleRequestBusy: false});
        })
    };

    joyrideCallback = (tour) => {
        const {action, index, type} = tour;

        if (type === EVENTS.STEP_AFTER) {
            if (index === 1) {
                this.setState({showCoupleStatisticModal: true});
            }
            if (index === 2) {
                this.setState({showNewStatisticModal: true});
            }
            if (index === 10) {
                this.setState({showNewStatisticModal: false});
            }
            if (index === 14) {
                this.setState({showCoupleStatisticModal: false});
            }
            this.setState({stepIndex: index + (action === ACTIONS.PREV ? -1 : 1)});
        }

        if(type === EVENTS.TOUR_END) {
            this.setState({runJoyride: false, stepIndex: 0});
        }
    };

    joyrideSteps = [
        {
            content: Lang.get('tips.help-steps.1'),
            target: "#step-1",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.2'),
            target: "#step-2",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.3'),
            target: "#step-3",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.4'),
            target: "#step-4",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.5'),
            target: "#step-5",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.6'),
            target: "#step-6",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.7'),
            target: "#step-7-and-8",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.8'),
            target: "#step-7-and-8",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.9'),
            target: "#step-9",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.10'),
            target: "#step-10",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.11'),
            target: "#step-11",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.12'),
            target: "#step-12",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.13'),
            target: "#step-13",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.14'),
            target: "#step-14",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.15'),
            target: "#step-15",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.16'),
            target: "#step-16",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.17'),
            target: "#step-17",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.18'),
            target: "#step-18",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.19'),
            target: "#step-19",
            disableBeacon: true,
        },
        {
            content: Lang.get('tips.help-steps.20'),
            target: "#step-20",
            disableBeacon: true,
        },
    ];


    toggleCoupleModal = () => {
        this.setState({showCoupleStatisticModal: !this.state.showCoupleStatisticModal});
        if (this.state.runJoyride && this.state.stepIndex === 1) {
            this.setState({stepIndex: 2});
        }
    };

    toggleNewStatisticModal = () => {
        this.setState({showNewStatisticModal: !this.state.showNewStatisticModal});
        if (this.state.runJoyride && this.state.stepIndex === 2) {
            this.setState({stepIndex: 3});
        }
    };

    render = () => {
        const {
            match,
            tip, coupledStatistics,
            statistics, educationProgramTypes,
            variableFilters, cohorts,
            coupleStatisticForm, updateCoupleStatisticFormProperty,
            coupledStatisticsInEditMode, storeNewCoupledStatistic,
            storeNewStatisticVariable, updateEntity,
            toggleEditModeForCoupledStatistic, decoupleStatistic
        } = this.props;

        if (tip === undefined) return <div>Loading...</div>;


        // Check if user has two different Educ types statistics coupled, should be discouraged
        let hasActingCoupled, hasProducingCoupled = false;
        coupledStatistics.forEach(coupled => {
            const stat = statistics[coupled.statistic];
            if (stat.education_program_type === 'acting') hasActingCoupled = true;
            if (stat.education_program_type === 'producing') hasProducingCoupled = true;
        });

        return <div>


            <div className="container">
            <Link to="/">
                <button type="button" className="btn">{Lang.get('tips.back')}</button>
            </Link>
                <button onClick={() => this.setState({runJoyride: true, stepIndex: 0})}
                        className="btn btn-danger pull-right">{Lang.get('tips.help-steps.guide')}</button>
            <br/>
            <h1>{Lang.get('tips.edit')}</h1>
                <div className="row">
                    <div className="col-lg-6">
                <input id="step-1" type="text" className="form-control" placeholder={Lang.get('tips.name')}
                       value={tip.name}
                       onChange={e => updateEntity('tips', tip.id, {...tip, name: e.target.value})}/>
                    </div>
            </div>


            {/* The list op coupled statistics for this tip */}

                <h3>{Lang.get('tips.coupled-statistics')}</h3>

                <button id="step-2" className="btn btn-primary"
                        onClick={this.toggleCoupleModal}>{Lang.get('statistics.couple')}
                </button>
                <br/><br/>

                <div className="row" style={{background: 'white', marginBottom: '10px', marginTop: '10px'}} id="step-16">
                    {hasProducingCoupled && hasActingCoupled &&
                    <div className="alert alert-danger text-danger" role="alert">
                        {Lang.get('statistics.acting-producing-coupled')}
                    </div>}
                    <div>
                        {
                            coupledStatistics.map(coupledStatistic => {
                                const statistic = statistics[coupledStatistic.statistic];

                                return <div className="col-md-4" key={coupledStatistic.id}><CoupledStatisticItem
                                    coupledStatistic={coupledStatistic}
                                    tip={tip}
                                    statistic={statistic}
                                    educationProgramType={educationProgramTypes[statistic.education_program_type]}
                                    editMode={coupledStatisticsInEditMode.includes(coupledStatistic.id)}
                                    updateEntity={updateEntity}
                                    toggleEditModeForCoupledStatistic={toggleEditModeForCoupledStatistic}
                                    decoupleStatistic={decoupleStatistic}
                                /></div>
                            })
                        }
                    </div>

                </div>


                <Modal open={this.state.showCoupleStatisticModal} little
                       onClose={() => this.setState({showCoupleStatisticModal: false})}
                       classNames={{'modal': "panel panel-default"}}>
                    <div className="panel-body">
                        <h3 style={{display: 'inline-block'}}>{Lang.get('tips.couple-statistic')}</h3>
                        <br/>


                        <strong>{Lang.get('statistics.select-statistic')}</strong>
                        <div className="row">
                            <div className="col-lg-6" id="step-12">
                                <select value={coupleStatisticForm.statistic} className="form-control"
                                        onChange={e => updateCoupleStatisticFormProperty('statistic', e.target.value)}>
                                    <option disabled={true}/>
                                    {

                                        allowedStatistics(tip, statistics, coupledStatistics).map(
                                            statistic => <option key={statistic.id}
                                                                 value={statistic.id}>

                                                {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                                                {statistic.type !== 'predefinedstatistic' && statistic.name}

                                                &nbsp;-&nbsp;
                                                ({statistic.education_program_type})
                                                {statistic.type === 'predefinedstatistic' && ' - (' + Lang.get('statistics.predefined') + ')'}
                                            </option>
                                        )
                                    }
                                </select>
                            </div>
                        </div>

                        <br/>

                        <strong>{Lang.get('statistics.when-active')}</strong>
                        <div className="row">
                            <div className="col-lg-3" id="step-13">
                                <select value={coupleStatisticForm.comparisonOperator} className="form-control"
                                        onChange={e => updateCoupleStatisticFormProperty('comparisonOperator', e.target.value)}>
                                    <option value="0">{Lang.get('statistics.greater-than')}</option>
                                    <option value="1">{Lang.get('statistics.less-than')}</option>
                                </select>
                            </div>
                            <div className="col-lg-3" id="step-14">
                                <input type="number" className="form-control" step="any"
                                       value={coupleStatisticForm.threshold}
                                       onChange={e => updateCoupleStatisticFormProperty('threshold', e.target.value)}/>
                            </div>
                        </div>

                        <br/><br/>

                        <div>
                            <button className="btn btn-primary" id="step-15"
                                    disabled={coupleStatisticForm.statistic === '' || coupleStatisticForm.threshold === '' || coupleStatisticForm.comparisonOperator > '2' || this.state.coupleRequestBusy}
                                    onClick={this.coupleStatistic}>
                                {this.state.coupleRequestBusy &&
                                <span className="glyphicon glyphicon-refresh glyphicon-refresh-animate"/>}
                                {!this.state.coupleRequestBusy && <span>{Lang.get('statistics.couple')}</span>}

                            </button>
                            &nbsp;
                            <button className="btn btn-danger"
                                    onClick={() => this.setState({showCoupleStatisticModal: false})}>
                                {Lang.get('statistics.cancel')}
                            </button>

                            <button className="pull-right btn btn-default" id="step-3"
                                    onClick={this.toggleNewStatisticModal}>{Lang.get('react.statistic.create-statistic')}
                            </button>
                        </div>
                    </div>
                </Modal>

                <Modal open={this.state.showNewStatisticModal} little
                       onClose={() => this.setState({showNewStatisticModal: false})}
                       classNames={{'modal': "panel panel-default"}}>
                    <div className="panel-body" id="step-8">
                    <h3>{Lang.get('react.statistic.create-statistic')}</h3>
                    <CreateForm variableFilters={variableFilters}
                                joyrideStepIndex={this.state.stepIndex}
                                educationProgramTypes={educationProgramTypes}
                                operators={[
                                    {type: 0, label: "+"},
                                    {type: 1, label: "-"},
                                    {type: 2, label: "*"},
                                    {type: 3, label: "/"},
                                ]}
                                onCreated={newEntity => {
                                    storeNewStatisticVariable(normalize(newEntity, Schema.statistic));
                                    if (this.state.runJoyride) {
                                        this.joyrideRef.helpers.next();
                                    }
                                    this.setState({showNewStatisticModal: false});
                                }}
                    />
                </div>
                </Modal>


                <div className="panel panel-default" id="step-17">
                    <div className="panel-body">
                        <div className="form-group">
                            <label>{Lang.get('tips.tiptext')}</label>
                            <textarea className="form-control" value={tip.tipText} maxLength={1000} rows={3}
                                      onChange={e => updateEntity('tips', tip.id, {...tip, tipText: e.target.value})}/>
                        </div>
                        <p>{Lang.get('tips.form.statistic-value-parameters')}</p>

                        {/* Table for value parameters */}
                        <table className="table">
                            <thead>
                            <tr>
                                <th>{Lang.get('tips.form.table-statistic')}</th>
                                <th>{Lang.get('tips.form.table-value-parameter')}</th>
                                <th>{Lang.get('tips.form.table-value-name-parameter')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                coupledStatistics.map(coupledStatistic => {

                                    const statistic = statistics[coupledStatistic.statistic];

                                    return <tr key={coupledStatistic.id}>
                                        <td>
                                            {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                                            {statistic.type !== 'predefinedstatistic' && statistic.name}
                                        </td>
                                        <td><strong>:statistic-{coupledStatistic.id}</strong></td>
                                        <td>
                                            {
                                                statistic.type === 'predefinedstatistic' &&
                                                <span>
                                    <strong>:statistic-name-{coupledStatistic.id}</strong>
                                    <br/>
                                                    {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-value-name-params.' + statistic.valueParameterDescription)}

                                </span>
                                            }
                                        </td>
                                    </tr>
                                })
                            }
                            </tbody>
                        </table>
                    </div>
                </div>
            <hr/>

                <div className="form-group" style={{background: 'white'}} id="step-18">
                    <input id='analysisvisible' type="checkbox" checked={tip.showInAnalysis}
                       onChange={e => updateEntity('tips', tip.id, {...tip, showInAnalysis: e.target.checked})}/>
                    <label htmlFor='analysisvisible'>&nbsp;{Lang.get('tips.form.showInAnalysis')}</label>
                </div>

            <hr/>

            <h3>{Lang.get('tips.form.cohorts-enable')}</h3>

                <div>
                    <div className="row" style={{background: 'white', marginBottom: '20px'}} id="step-19">
                        <div className="col-md-10">
                            <label>{Lang.get('tips.form.enabledCohorts')}</label>
                            <div className="row">
                                {Object.values(cohorts).filter(cohort => {
                                    if (tip.coupled_statistics.length === 0) return true;
                                    const epMapping = {
                                        2: 'acting',
                                        1: 'producing',
                                    };
                                    const firstCoupledStatistic = coupledStatistics[0];
                                    const statistic = statistics[firstCoupledStatistic.statistic];
                                    const epTypeName = statistic.education_program_type;
                                    return epMapping[cohort.ep_id] === epTypeName;
                                }).sort().map(cohort => {
                                    return <div className="col-md-2" key={cohort.id}>
                                        <div className="form-group"><p className="checkbox">
                                            <label><input type="checkbox"
                                                          checked={tip.enabled_cohorts.includes(cohort.id)}
                                                          onChange={e => {
                                                              const enabled = [...tip.enabled_cohorts];
                                                              if (enabled.includes(cohort.id)) {
                                                                  enabled.splice(enabled.indexOf(cohort.id), 1);
                                                              } else {
                                                                  enabled.push(cohort.id);
                                                              }
                                                              updateEntity('tips', tip.id, {
                                                                  ...tip,
                                                                  enabled_cohorts: enabled
                                                              });
                                                          }}
                                            /> {cohort.name}
                                            </label></p>
                                        </div>
                                    </div>;
                                })}
                            </div>
                        </div>
                    </div>


                    <div style={{background: 'white'}} id="step-20">
                        <button disabled={tip.name === '' || tip.tipText === '' || this.state.submitting}
                                className="btn btn-primary" onClick={this.save}>
                            {this.state.submitting &&
                            <span className="glyphicon glyphicon-refresh glyphicon-refresh-animate"/>}
                            {!this.state.submitting && <span>{Lang.get('tips.save')}</span>}

                        </button>
                    </div>
                </div>

        </div>
            <Joyride
                ref={ref => {
                    this.joyrideRef = ref
                }}
                steps={this.joyrideSteps}
                run={this.state.runJoyride}
                hideBackButton={true}
                continuous={true}
                spotlightClicks={true}
                spotlightPadding={5}
                showProgress
                stepIndex={this.state.stepIndex}
                callback={this.joyrideCallback}
                styles={{
                    options: {zIndex: 99999}
                }}
                locale={{ back: Lang.get('tips.help-steps.back'), close: Lang.get('tips.help-steps.close'), last: Lang.get('tips.help-steps.last'), next: Lang.get('tips.help-steps.next'), skip: Lang.get('tips.help-steps.skip') }}
            />
        </div>
    };

    save = () => {
        const {tip} = this.props;
        this.setState({submitting: true, saveButtonText: "..."});
        axios.put(`/api/tips/${tip.id}`, tip).then(response => {
            this.setState({submitting: false, saveButtonText: Lang.get('general.saved')});
            setTimeout(() => this.setState({saveButtonText: Lang.get('general.save')}), 2000);
        });
    }

}


const CoupledStatisticItem = ({tip, coupledStatistic, statistic, educationProgramType, editMode, updateEntity, toggleEditModeForCoupledStatistic, decoupleStatistic}) => {

    const saveCoupledStatistic = () => {
        axios.put(`/api/tip-coupled-statistics/${coupledStatistic.id}`, {
            threshold: coupledStatistic.threshold,
            comparison_operator: coupledStatistic.comparison_operator,

        }).then(response => {
            const normalizedCoupledStatistic = normalize(response.data, Schema.coupledStatistic).entities.coupledStatistics[coupledStatistic.id];
            updateEntity('coupledStatistics', coupledStatistic.id, normalizedCoupledStatistic);
            toggleEditModeForCoupledStatistic(coupledStatistic.id);
        });
    };

    const decouple = () => {
        axios.delete(`/api/tip-coupled-statistics/${coupledStatistic.id}`).then(response => {
            decoupleStatistic(coupledStatistic);
        });
    };

    // Render the normal display form
    if (!editMode) {
        return <div className="panel panel-default" style={{flex: '1'}}>
            <div className="panel-body">
                <div>
                    <h5>
                        {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                        {statistic.type !== 'predefinedstatistic' && statistic.name}
                    </h5>

                    <strong>{Lang.get('tips.ep-type')}:</strong> {statistic.education_program_type}<br/>
                    <strong>{Lang.get('tips.condition')}: </strong>{coupledStatistic.condition}<br/>
                </div>
                <br/>
                <button className="btn btn-primary"
                        onClick={() => toggleEditModeForCoupledStatistic(coupledStatistic.id)}>{Lang.get('general.edit')}</button>
            </div>
        </div>;
    }

    // Render the edit form for a coupled statistic
    return <div className="panel panel-default" style={{flex: '1'}}>
        <div className="panel-body">
            <div>
                <h5>
                    {statistic.type === 'predefinedstatistic' && Lang.get('statistics.predefined-stats.' + statistic.name)}
                    {statistic.type !== 'predefinedstatistic' && statistic.name}
                </h5>

                <strong>{Lang.get('statistics.when-active')}</strong>
                <div className="row">
                    <div className="col-lg-6" id="step-13">
                        <select
                            value={coupledStatistic.comparison_operator} className="form-control"
                            onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                                ...coupledStatistic,
                                comparison_operator: e.target.value
                            })}>
                            <option value="0">{Lang.get('statistics.greater-than')}</option>
                            <option value="1">{Lang.get('statistics.less-than')}</option>
                        </select>
                    </div>
                    <div className="col-lg-6">
                        <input type="number" className="form-control" step="any" value={coupledStatistic.threshold}
                               onChange={e => updateEntity('coupledStatistics', parseInt(coupledStatistic.id), {
                                   ...coupledStatistic,
                                   threshold: e.target.value
                               })}/>
                    </div>
                </div>

            </div>
            <br/>
            <div style={{display: 'flex', flexDirection: 'row'}}>
                <button className="btn btn-primary" onClick={saveCoupledStatistic}>{Lang.get('tips.save')}</button>
                &nbsp;
                <button className="btn btn-danger" onClick={decouple}>{Lang.get('tips.decouple')}</button>
                &nbsp;
                <button className="btn"
                        onClick={() => toggleEditModeForCoupledStatistic(coupledStatistic.id)}>{Lang.get('tips.cancel')}</button>
            </div>
        </div>

    </div>;


};

/**
 * Get the selectable statistics for this tip.
 */
const allowedStatistics = (tip, statistics) => Object.values(statistics)
    .filter(statistic =>
        String(statistic.id).startsWith('p-p-') ||
        String(statistic.id).startsWith('p-a-') ||
        statistic.type === 'customstatistic');

const mapping = {
    state: (state, props) => {
        const tip = state.entities.tips[props.match.params.id];
        // Signal whether we're loading
        if (tip === undefined) return {tip: undefined};
        const coupledStatistics = tip.coupled_statistics.sort().map(id => state.entities.coupledStatistics[id]);
        return {
            tip,
            coupledStatistics,
            statistics: state.entities.statistics,
            educationProgramTypes: state.entities.educationProgramTypes,
            variableFilters: state.tipEditPageUi.variableFilters,
            cohorts: state.entities.cohorts,
            coupleStatisticForm: state.coupleStatistic,
            coupledStatisticsInEditMode: state.tipEditPageUi.inEditMode
        };
    },
    dispatch: dispatch => ({
        updateCoupleStatisticFormProperty: (property, value) => dispatch(coupleStatisticActions.updateCoupleStatisticFormProperty(property, value)),
        storeNewCoupledStatistic: normalized => {

            dispatch(entityActions.addEntities(normalized.entities));
            dispatch(entityActions.addCoupledStatisticToTip(normalized.result, normalized.entities.coupledStatistics[normalized.result].tip_id));
        },
        storeNewStatisticVariable: normalized => {
            dispatch(entityActions.addEntities(normalized.entities));
        },
        updateEntity: (name, key, entity) => dispatch(entityActions.updateEntity(name, key, entity)),
        toggleEditModeForCoupledStatistic: id => dispatch(uiActions.toggleEditModeCoupledStatistic(id)),
        decoupleStatistic: coupledStatistic => dispatch(entityActions.decoupleStatisticFromTip(coupledStatistic)),
    })
};

export default connect(mapping.state, mapping.dispatch)(TipEditPage);

