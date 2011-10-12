<?
require_once('ActiveResource.php');

class opKakiageRedmineIssue
{
  var $issue;

  public function __construct($site)
  {
    $this->issue = new Issue();
    if (isset($site))
    {
      $this->issue->site = $site;
    }
  }

  public function setSite($site)
  {
    $this->issue->site = $site;
  }

  public function getIssues($userId)
  {
    if (!isset($this->issue->site))
    {
      throw new LogicException('not set site');
    }
    $issues = $this->issue->find('all', array(
      'assigned_to_id' => $userId,
    ));
    $results = array();
    foreach ($issues as $is)
    {
      $results[] = array(
        'status' => $is->status->attributes()->name,
        'tracker' => $is->tracker->attributes()->name,
        'title' => $is->subject,
        'url' => $this->issue->site.'issues/'.$is->id,
      );
    }

    return $results;
  }

  public function getIssuesToString($userId, $isTitleTruncate = false, $trancateLength = 20)
  {
    $issues = $this->getIssues($userId);
    $result = "";
    foreach ($issues as $issue)
    {
      if ($isTitleTruncate)
      {
        $title = $issue['title'];
        $issue['title'] = mb_substr($title, 0, $trancateLength).(mb_strlen($title)>$trancateLength?'...':'');
      }
      $result .= implode(' ', $issue)."\n";
    }

    return $result;
  }
}

class Issue extends ActiveResource
{
  var $request_format = 'json';
}
