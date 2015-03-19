#!/bin/bash
repositoryName=im.aiai.prediction
repositoryPath=https://git.oschina.net/sunguide/$repositoryName
version=$2
echo $repositoryPath;
git archive -o $repositoryName$version --remote $repositoryPath HEAD  | tar -x -C /root/else
