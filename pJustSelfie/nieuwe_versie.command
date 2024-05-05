#/usr/bin/bash
cd -- "$(dirname "$0")"

 php generate.php
 git add -A .
 git commit -am "Just Selfie SFU Prod | #22"
 git push
