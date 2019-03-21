<?php

include_once 'Controller.php';

/**
 * This controller performs operations on Bet status.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */
class StatusController extends Controller
{
    /**
     * This function validates is user id exist and then create list of bets.
     */
    public function listOfBets()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        //Check if user id insert.
        if ($data['clientId'] != "")
        {
            $clientId = $data['clientId'];

            //Check if insert id exists in database.
            $querySelectClient = "SELECT * FROM clients WHERE id=" . $clientId;
            $dataClient = self::connection()
                ->query($querySelectClient)
                ->fetch();

            if ($dataClient == 0)
            {
                $message = "Error! User id not found.";
                self::services()->message("error",$message);
            }
            else
            {
                //If insert id exist,function create list of bets for this user id.
                $querySelectBets = "SELECT * FROM bets WHERE client_id=(?) ORDER BY placed_date DESC";
                $SelectBets = self::connection()->prepare($querySelectBets);
                $SelectBets->execute(array
                (
                    $clientId,
                ));

                $dataBets = $SelectBets->fetchAll(PDO::FETCH_ASSOC);

                if ($dataBets == 0) {
                    $message = "There is no bet.";
                    self::services()->message("error",$message);
                }
                else
                {
                    for ($i = 0; $i < count($dataBets); $i++) {
                        $dataBets[$i]['combination'] = unserialize($dataBets[$i]['combination']);
                        if ($dataBets[$i]['draw_id'] != null) {
                            $querySelectWinCombination = "SELECT * FROM draws WHERE id=" . $dataBets[$i]['draw_id'];
                            $winCombination = self::connection()
                                ->query($querySelectWinCombination)
                                ->fetch(PDO::FETCH_ASSOC);

                            $dataBets[$i]['winCombination'] = unserialize($winCombination['combination']);
                        }
                        else
                        {
                            $dataBets[$i]['winCombination'] = "-";
                        }
                    }

                    $array = array
                    (
                        "listOfBets" => $dataBets,
                        "message" => ""
                    );
                    self::services()->returnJson($array);
                }
            }
        }
        else
        {
            $message = "Error! User id not insert.";
            self::services()->message("error",$message);
        }
    }
}