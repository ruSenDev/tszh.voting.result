<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));
CJSCore::Init(array("ajax"));

if (!empty($arResult['ERRORS']['VOTE'])) {
    foreach ($arResult['ERRORS']['VOTE'] as $key => $message) {
        ShowError($message . " ID=" . $key);
    }
}

foreach ($arResult['VOTE'] as $VOTE):

    if (isset($VOTE['QUESTION']) && !empty($VOTE['QUESTION'])):

        $idVoting = $VOTE["ID"];
        $this->AddEditAction($idVoting, '/bitrix/admin/vdgb_tszhvoting_edit.php?bxpublic=Y&lang=' . LANGUAGE_ID . '&ID=' . $idVoting, GetMessage("CVF_EDIT_VOTING"));
        $this->AddDeleteAction($idVoting, '/bitrix/admin/vdgb_tszhvoting.php?bxpublic=Y&del_element=' . $idVoting . '&lang=' . LANGUAGE_ID . '&set_default=Y', GetMessage("CVF_DELETE_VOTING"));

        ?>


        <div class="voting-result" id="<?= $this->GetEditAreaId($idVoting); ?>">
            <?
            if (strlen($VOTE["TITLE_TEXT"])) {
                ?>
                <div class="voting-text"><?= $VOTE["TITLE_TEXT"] ?></div><?
            }
            ?>
            <div class="voting-date">
                <?= GetMessage("CVF_DATES") ?>:
                <?= CCitrusPolls::formatPeriod($VOTE['DATE_BEGIN'], $VOTE['DATE_END']) ?><br>
            </div>
            <?
            if (strlen(trim(strip_tags($VOTE["DETAIL_TEXT"])))) {
                ?>
                <div class="voting-description"><?= htmlspecialcharsback($VOTE["DETAIL_TEXT"]); ?></div><?
            }
            ?>

            <? foreach ($VOTE['QUESTION'] as $key => $question):

                $idQuest = $question["ID"];
                $this->AddEditAction($idVoting . '/' . $idQuest, '/bitrix/admin/vdgb_tszhvoting_question_edit.php?bxpublic=Y&lang=' . LANGUAGE_ID . '&VOTING_ID=' . $idVoting . '&ID=' . $idQuest, GetMessage("CVF_EDIT_QUESTION"));

                $arAnswerSums = Array();
                $arTotals = Array();
                $rsAnswers = CCitrusPollVoteAnswer::GetList(Array(), Array("VOTE_QUESTION_ID" => $question['ID']), Array(
                    "ANSWER_ID",
                    "VOTE_QUESTION_ID",
                    "SUM" => 'VOTE_WEIGHT',
                ));
                while ($arAnswer = $rsAnswers->GetNext(false)) {
                    $arAnswerSums[$arAnswer['ANSWER_ID']] = Array(
                        "SUM" => $arAnswer['VOTE_WEIGHT'],
                        'CNT' => $arAnswer["CNT"],
                    );
                    $arTotals[$arAnswer['VOTE_QUESTION_ID']] += $arAnswer['VOTE_WEIGHT'];
                }

                ?>
                <? if (isset($question['ANSWER'])): ?>


                <div class="voting-question" id="<?= $this->GetEditAreaId($idVoting . '/' . $idQuest); ?>">
                    <div class="voting-header"><?= $question['TEXT'] ?></div>
                    <div>
                        <? if ($arParams['VOTE_TYPE_DIOGRAM']): ?>
                            <table class="voting-result-diagram">
                                <? foreach ($question['ANSWER'] as $answ_id => $answer): ?>
                                    <? if (!is_null($arTotals[$question['ID']]) && !is_null($arAnswerSums[$answ_id]['SUM'])) : ?>
                                        <?
                                        $sum = $arAnswerSums[$answ_id]['SUM'];
                                        $count = (100 * $sum) / $arTotals[$question['ID']];
                                        $width = $count * 0.8;
                                        ?>
                                        <tr>
                                            <td width="20%">1111<?= $answer['TEXT'] ?></td>
                                            <td width="75%">
                                                <div class="voting-result-box"
                                                     style="width:<?= $width ?>%;height:15px;background-color:<?= $answer['COLOR'] ?>;"></div>
                                                <span class="voting-result-percent"><?= number_format($count, 2, ".", " ") . "%" ?></span>
                                            </td>
                                        </tr>
                                    <? else : ?>
                                        <tr>
                                            <td colspan="2">222<?= $answer['TEXT'] ?></td>
                                        </tr>
                                    <? endif; ?>
                                <? endforeach; ?>
                            </table>
                        <? else:

                            ?>
                            <table class="voting-result-diagram">
                                <tr>
                                    <td width="30%" class="img-diagram">
                                        <img alt="" width="150" height="150"
                                             src="<?= $componentPath ?>/draw_graf.php?QUESTION=<?= $key ?>&dm=150"
                                             class="voting-result-diagram" id="img-diagram"/>
                                    </td>
                                    <td>
                                        <div class="answer-box">
                                            <? foreach ($question['ANSWER'] as $answ_id => $answer):
                                                $sum = $arAnswerSums[$answ_id]['SUM'];
                                                $count = (100 * $sum) / $arTotals[$question['ID']];
                                                ?>

                                                <div class="answer-box-result <? if (isset($answer['USERS_VOTES'])): ?> cursor<? endif; ?>">
                                                    <div class="answer-box-result-text">
                                                        <div class="voting-result-box"
                                                             style="background-color:<?= $answer['COLOR'] ?>;"></div>
                                                        <span class="voting-result-percent"><?= number_format($count, 2, ".", " ") . "%" ?> (<?= count($answer['USERS_VOTES']) ?>)</span><span
                                                                class="answer-span"> <?= $answer['TEXT'] ?></span>


                                                        <? if (isset($answer['USERS_VOTES'])): ?>
                                                            <div class="divTable not-visible">
                                                                <div class="divTableBody">
                                                                    <div class="divTableRow header">
                                                                        <div class="divTableCell">#</div>
                                                                        <div class="divTableCell"><?= GetMessage("CVF_VOTE_FIO") ?></div>
                                                                        <div class="divTableCell"><?= GetMessage("CVF_VOTE_LC") ?></div>
                                                                        <div class="divTableCell"><?= GetMessage("CVF_VOTE_ADDRESS") ?></div>
                                                                    </div>
                                                                    <? $i = 1 ?>
                                                                    <? foreach ($answer['USERS_VOTES'] as $key => $votes): ?>
                                                                        <div class="divTableRow">
                                                                            <div class="divTableCell"><?= $i++ ?></div>
                                                                            <div class="divTableCell"><?= $votes["NAME"] ?></div>
                                                                            <div class="divTableCell"><?= $votes["XML_ID"] ?></div>
                                                                            <div class="divTableCell"><?= $votes["STREET"] . ", " . $votes["HOUSE"] . ", кв." . $votes["FLAT"] ?></div>
                                                                        </div>
                                                                    <? endforeach; ?>
                                                                </div>
                                                            </div>
                                                        <? endif; ?>

                                                    </div>
                                                </div>


                                            <? endforeach; ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>


                        <? endif; ?>
                    </div>
                </div>

            <? endif; ?>
            <? endforeach; ?>
        </div>
    <? endif ?>
<? endforeach ?>