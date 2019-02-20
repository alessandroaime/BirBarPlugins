#!/usr/bin/php

<?php

# <bitbar.title>JIRA Open Issues</bitbar.title>
# <bitbar.version>v1.0</bitbar.version>
# <bitbar.author>Alessandro Aime (alessandro.aime@hotmail.com)</bitbar.author>
# <bitbar.author.github>alessandroaime</bitbar.author.github>
# <bitbar.desc></bitbar.desc>

$server = "SERVER";
$username = "USERNAME";
$password = "PASSWORD";

$curl = curl_init();
curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

$api = "rest/api/2/search?jql";
$params = "assignee=currentuser()&state=open&maxResults=250&fields=key,description,priority,project,status,summary";
$url = "https://$server/$api=$params";

curl_setopt($curl, CURLOPT_URL, $url);
$json = json_decode(curl_exec($curl), true);

$openIssues = 0;
$projects = array();

$projectId = 0;
$projectIndex = -1;
$issueIndex = 0;

foreach ($json['issues'] as $issue) {
  if($issue['fields']['status']['statusCategory']['id'] == 5) {
    if($projectId != $issue['fields']['project']['id']) {
      $projectId = $issue['fields']['project']['id'];
      $projectIndex += 1;
      $issueIndex = 0;
    }
    $openIssues += 1;
    $projects[$projectIndex][$issueIndex] = $issue;
    $issueIndex += 1;
  }
}

if($openIssues == 0) {
  echo "No issues";
  exit;
} else {
  echo $openIssues." issue".($openIssues==1 ? "" : "s");
}
echo "\r\n"."---"."\r\n";


usort($projects, function($project1, $project2) {
  return $project1[0]['key'] <=> $project2[0]['key'];
});
foreach ($projects as $project) {
  $priorityId = -1;
  usort($project, function($issue1, $issue2) {
    return $issue1['key'] <=> $issue2['key'];
  });
  usort($project, function($issue1, $issue2) {
    return $issue1['fields']['priority']['id'] <=> $issue2['fields']['priority']['id'];
  });
  echo $project[0]['fields']['project']['name'];
  echo "\r\n"."----";
  echo "--";
  echo $issue['key'].": ".$issue['fields']['summary']."| href=https://".$server."/browse/".$issue['key'];
  echo "\r\n";
  foreach ($project as $issue) {
    if($priorityId != $issue['fields']['priority']['id']) {
      echo "--";
      echo $issue['fields']['priority']['name'];
      $priorityId = $issue['fields']['priority']['id'];
      echo "\r\n";
    }
    echo "--";
    echo $issue['key'].": ".$issue['fields']['summary']."| href=https://".$server."/browse/".$issue['key'];
    echo "\r\n";
  }
}

?>
