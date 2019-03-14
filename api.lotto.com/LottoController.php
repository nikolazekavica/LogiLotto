<?php
/**
 * Created by PhpStorm.
 * User: nikol
 * Date: 12.3.2019.
 * Time: 18:11
 */

include 'Controller.php';

/**
 * This controller is main Controller and they performs engine functions on lotto game.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */

class LottoController extends Controller
{
    /** Generating seven unique numbers*/
    function randomGenerateSevenNumbers()
    {
        return self::services()->uniqueRandomNumbers(1,60,7);
    }

    /** Store draw in database*/
    public function storeDrawnNumbers()
    {
        $winCombination  = self::randomGenerateSevenNumbers();

        $queryCreateDraw = "INSERT INTO draws(combination) VALUES(?);";
        $dataCreateDraw  = self::connection()->prepare($queryCreateDraw);
        $dataCreateDraw->execute(array
        (
            serialize($winCombination)
        ));
    }

    /**
     *This function set draw id to active bets in database,update bets,update user cash status and do transactions if
     * bet status is 'win'.
    */
    public function doSettlement()
    {
        //Select las draw.
        $queryLastDraw   = "SELECT * FROM draws ORDER BY id DESC LIMIT 1";
        $dataDraw        = self::connection()->query($queryLastDraw)->fetch();
        $drawId          = $dataDraw['id'];
        $drawCombination = unserialize($dataDraw['combination']);

        //Select active bets.
        $queryActiveBets = "SELECT * FROM bets WHERE status='active'";
        $dataActiveBets  = self::connection()
                               ->query($queryActiveBets)
                               ->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($dataActiveBets); $i++)
        {
            $activeCombination    = unserialize($dataActiveBets[$i]['combination']);
            $numberOfMatchedBalls = count(array_intersect($activeCombination,$drawCombination));

            if($numberOfMatchedBalls==0)
            {
                //Update bet status in database.
                $queryChangeBetStatus  = "UPDATE bets SET status = 'LOSE' WHERE id=(?);";
                $changeBetStatus       = self::connection()->prepare($queryChangeBetStatus);

                $changeBetStatus->execute(array
                (
                    $dataActiveBets[$i]['id']
                ));

                //Set draw id to active bets.This is important for displaying draws in bet history table.
                $queryAddDrawId = "UPDATE bets SET draw_id = (?) WHERE id=(?);";
                $AddDrawId      = self::connection()->prepare($queryAddDrawId);

                $AddDrawId->execute(array
                (
                    $drawId,
                    $dataActiveBets[$i]['id']
                ));
            }
            else
            {
                $winCash = self::services()->cashCalculator
                (
                    $numberOfMatchedBalls,
                    $dataActiveBets[$i]['stake_amount']
                );

                $queryAddDrawId = "UPDATE bets SET draw_id = (?) WHERE id=(?);";
                $AddDrawId      = self::connection()->prepare($queryAddDrawId);
                $AddDrawId->execute(array
                (
                    $drawId,
                    $dataActiveBets[$i]['id']
                ));

                //Update bet status in database.
                $queryUpdateBetStatus  = "UPDATE bets SET status = 'WIN',win_amount =(?) WHERE id=(?);";
                $changeBetStatus       = self::connection()->prepare($queryUpdateBetStatus);
                $changeBetStatus->execute(array
                (
                    $winCash,
                    $dataActiveBets[$i]['id']
                ));

                //Create transaction if bet status is 'win' in database.
                $queryAddTransaction   = "INSERT INTO transactions(bet_id,transaction_type,amount)VALUES(?,?,?);";
                $addTransaction        = self::connection()->prepare($queryAddTransaction);
                $addTransaction->execute(array
                (
                    $dataActiveBets[$i]['id'],
                    "win",
                    $winCash
                ));

                //Update user cash status if bet status is 'win' in database.
                $queryUpdateClientCash = "UPDATE clients SET cash = cash + (?) WHERE id=(?);";
                $updateClientCash      = self::connection()->prepare($queryUpdateClientCash);
                $updateClientCash->execute(array
                (
                    $winCash,
                    $dataActiveBets[$i]['client_id']
                ));
            }
        }

        $array = array
        (
            "winCombination" => $drawCombination
        );
        self::services()->returnJson($array);
    }

    public function sleep()
    {
        sleep(180);
    }
}