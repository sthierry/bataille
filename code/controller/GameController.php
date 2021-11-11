<?php

namespace Bataille\Controller;

use Bataille\Manager\GameEngineManager;
use Bataille\Model\PlayerModel;
use Bataille\Etc\DependencyInjectionContainer;

/**
 * @name \Bataille\Controller\GameController
 */
class GameController
{
    private GameEngineManager $gameEngineManager;
    private DependencyInjectionContainer $dependencyInjectionContainer;

    /**
     * @param \Bataille\Manager\GameEngineManager $gameEngineManager
     * @param \Bataille\Etc\DependencyInjectionContainer $dependencyInjectionContainer
     */
    public function __construct(GameEngineManager $gameEngineManager, DependencyInjectionContainer $dependencyInjectionContainer)
    {
        $this->gameEngineManager = $gameEngineManager;
        $this->dependencyInjectionContainer = $dependencyInjectionContainer;
    }

    public function indexAction()
    {
        $this->gameEngineManager->populateMainDeck();
        $playersArray = [];

        // for ($i=1;$i<=3;$i++) {
        //     /**
        //      * @var PlayerModel
        //      */
        //     $player = $this->dependencyInjectionContainer->instanciateClass(PlayerModel::class);
        //     if(!$player instanceof PlayerModel) {return;}
        //     $player->setId($i);
        //     $player->setName('player '.$i);
        //     $playersArray[] = $player;
        // }
        //todo découper indexAction en plusieurs Action renvoyant chacune vers des view.
        $nbPlayer = 2;
        for ($i=1;$i<=$nbPlayer;$i++) {
            echo 'Enter player '.$i.' name [default = player '.$i.'] : ';
            $name = fgets(STDIN);
            $name = substr($name,0, -1); //fgets(STDIN) leave an EOL at the end of string.
            var_dump($name);
            if($name == '') {
                $name = 'player '.$i;
            }
            $player = $this->dependencyInjectionContainer->instanciateClass(PlayerModel::class);
            $player->setId($i);
            $player->setName($name);
            $playersArray[] = $player;
        }

        //TODO : Mettre le playersArray directement dans le gameEngineManager et déplacer la méthode ci-dessus sous le nom de populatePlayerArray
        $this->gameEngineManager->distributeDeck($playersArray);

        while($result = $this->gameEngineManager->confrontCard($playersArray)) {
            echo " ".PHP_EOL;
            echo "Confronting cards : ".PHP_EOL;
            foreach ($result->getArrScores() as $arrScore) {
                foreach ($arrScore as $score) {
                    echo 'Player '.$score['player']->getName().' picked card '.$score['pickedCard']->getName().PHP_EOL;
                }
            }
            echo 'And the winner of this confrontation is '.$result->getWinner()->getName().PHP_EOL;
        }

        echo PHP_EOL;
    }
}