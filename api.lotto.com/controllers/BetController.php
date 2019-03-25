<?php

include_once 'Controller.php';

/**
 * This controller performs operations on Bet.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */
class BetController extends Controller
{
    /**
     * Controller first create validation and then insert bet in database,update user cash status and do transaction.
     * If everything is fine,controller send response message with user cash status and that bet is successfully create.
     */
    public function createBet()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $checkCombination = true;
        foreach ($data['combination'] as $key) {
            if (!(is_numeric($key))) {
                $checkCombination = false;
                break;
            }
        }

        //Check if all parameters insert.
        if ($data['clientId'] != "" &&
            preg_match('/^[1-9][0-9]{0,15}$/', $data['clientId']) &&
            !empty($data['combination']) &&
            $data['stakeAmount'] > 0
        ) {
            //Check if all values from combination are integer.
            if( $checkCombination == false){
                $message = "Error! Some value in combination is not integer.";
                self::services()->message("error",$message);
                exit;
            }

            $clientId    = $data['clientId'];
            $combination = $data['combination'];
            $stakeAmount = $data['stakeAmount'];
            $status      = "ACTIVE";
            $winAmount   = "0";
            $count       = count($combination);

            $querySelectClient = "SELECT id,cash FROM clients WHERE id=(?)";
            $dataSelectClient  = self::connection()->prepare($querySelectClient);
            $dataSelectClient->execute(array
            (
                $clientId,
            ));
            $selectClient = $dataSelectClient->fetch();
            $cash = $selectClient['cash'];

            //Check if bet combination has numbers from 1 to 60,if user id exists,if user has enough money for
            //bet and if bet combination has max seven numbers.
            if (max($combination) > 60 || min($combination) < 1)
            {
                $message = "Error! Some number in bet is bigger then 60 or less then 1.";
                self::services()->message("error",$message);
            }
            else if ($selectClient == 0)
            {
                $message = "Error! User id not found.";
                self::services()->message("error",$message);
            }
            else if ($cash < $stakeAmount)
            {
                $message = "Error! You do not have enough money.";
                self::services()->message("error",$message);
            }
            else if ($count > 7)
            {
                $message = "Error! You can not select more than seven numbers.";
                self::services()->message("error",$message);
            }
            else
            {
                //Insert bet in database.
                $queryCreateBet = "INSERT INTO bets(client_id,combination,status,stake_amount,win_amount) VALUES(?,?,?,?,?);";
                $createBet = self::connection()->prepare($queryCreateBet);
                $createBet->execute(array
                (
                    $clientId,
                    serialize($combination),
                    $status,
                    $stakeAmount,
                    $winAmount
                ));

                //Update cash status of user in database.
                $cashStatus = $cash - $stakeAmount;

                $queryUpdateCash = "UPDATE clients SET cash=(?) WHERE id=(?)";
                $updateCash = self::connection()->prepare($queryUpdateCash);
                $updateCash->execute(array
                (
                    $cashStatus,
                    $clientId
                ));

                //Get bet id for transaction.
                $querySelectBetId = "SELECT id FROM bets where client_id=(?) ORDER BY placed_date DESC limit 1;";
                $dataSelectBet = self::connection()->prepare($querySelectBetId);
                $dataSelectBet->execute(array
                (
                    $clientId,
                ));
                $selectBet = $dataSelectBet->fetch();

                //Create transaction.
                $transaction_type = "bet";
                $queryCreateTransaction = "INSERT INTO transactions(bet_id,transaction_type,amount) VALUES(?,?,?);";
                $dataCreateTransaction = self::connection()->prepare($queryCreateTransaction);
                $dataCreateTransaction->execute(array
                (
                    $selectBet['id'],
                    $transaction_type,
                    $stakeAmount
                ));

                $message = "Bet create successfully.You have " . $cashStatus . " euros on account.";
                self::services()->message("success",$message);
            }
        } else {
            $message = "Error! Some values not insert or not valid.";
            self::services()->message("error",$message);
        }
    }
}