<?php

class opRichTextareaKakiageExtension extends opWidgetFormRichTextareaOpenPNEExtension
{
  static public function getButtons()
  {
    return array(
      'op_kakiage_copy_from_previous_day' => array(
        'caption' => 'Copy from previous day',
        'imageURL' => image_path('/opKakiagePlugin/images/deco_op_kakiage_copy_from_previous_day.png'),
      ),
      'op_kakiage_nocall' => array(
        'caption' => '%nocall',
        'imageURL' => image_path('/opKakiagePlugin/images/deco_op_kakiage_nocall.png'),
      ),
      'op_kakiage_get_redmine_ticket' => array(
        'caption' => 'get your redmine ticket',
        'imageURL' => image_path('/opKakiagePlugin/images/redmine_fluid_icon.png'),
      ),
    );
  }

  static public function getButtonOnClickActions()
  {
    return array(
      'op_kakiage_copy_from_previous_day' => 'var a=$("kakiage_body"),b=$$("textarea.kakiage_body")[0];a.setValue($F(a)?$F(a)+"\n"+$F(b):$F(b));',
      'op_kakiage_nocall' => 'var a=$("kakiage_body");/^%%nocall/.test($F(a))||a.setValue("%%nocall\n"+$F(a));',
      'op_kakiage_get_redmine_ticket' => 'var a=$("kakiage_body");a.setValue("'.self::getRedmineIssueString().'");',
    );
  }

  static private function getRedmineIssueString()
  {
    $table = Doctrine::getTable('MemberProfile');
    $memberId = sfContext::getInstance()->getUser()->getMemberId();
    $site = $table->retrieveByMemberIdAndProfileName($memberId, 'redmine_url');
    $redmineIdProfile = Doctrine::getTable('MemberProfile')->retrieveByMemberIdAndProfileName($memberId, 'redmine_id');

    $issue = new opKakiageRedmineIssue($site ? $site : 'https://redmine.openpne.jp/');
    $str = is_null($redmineIdProfile) ? 'please input redmine_url and redmine_id in profile page' : $issue->getIssuesToString($redmineIdProfile['value'], true);
    return $str;
  }
}
