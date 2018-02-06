<?php


namespace App\Tips;


use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\Variables\CollectedDataStatisticVariable;
use App\Tips\Statistics\Variables\StatisticStatisticVariable;

class StatisticVariableService
{
    public function __construct()
    {

    }

    public function createStatisticVariable(array $data, $parameter = null) {
        switch($data['type']) {
            case (new CollectedDataStatisticVariable())->getType():
                return $this->createCollectedDataStatisticVariable($data['name'], $data['method'], $parameter);
            case (new StatisticStatisticVariable())->getType():
                return $this->createStatisticStatisticVariable((new CustomStatistic)->find($data['id']));
        }

        throw new \Exception("Unknown statistic variable type: {$data['type']}");
    }

    private function createStatisticStatisticVariable(CustomStatistic $statistic) {
        $variable = new StatisticStatisticVariable();
        $variable->name = $statistic->name;
        $variable->nestedStatistic()->associate($statistic);

        return $variable;
    }

    /**
     * @param string $name
     * @param string $method
     * @param string $parameter
     * @return CollectedDataStatisticVariable
     */
    private function createCollectedDataStatisticVariable($name, $method, $parameter) {
        $variable = new CollectedDataStatisticVariable();
        $variable->name = $name;
        $variable->dataUnitMethod = $method;
        $variable->dataUnitParameterValue = $parameter;

        return $variable;
    }
}