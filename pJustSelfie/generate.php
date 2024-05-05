<?php
// imcat_name_ver.psd
// for file in *.psd
// do
//  mv "$file" "${file%.psd}.png"
// done

/*
for file in *.png
do
number=$(echo $file | egrep -o '[[:digit:]]{1,4}' | head -n1)
number=$((number+=1))
mv "$file" "${file%_*}_$number.png"
done
 */

foreach (glob("*.{png,gif,apng}", GLOB_BRACE) as $file) {
	if (!is_dir($file)) {
		$data = explode("_", $file);
		if (!is_numeric($data[2])) {
			$num = explode(".", $data[2])[0];
			if (!is_numeric($num)) {
				echo var_dump(array($data), $num);
				exit("Failed to decode");
			}
			$data[2] = $num;
		}
		$newVer = $data[2] + 1;

		rename($file,
			sprintf(
				"%s_%s_%s.png",
				$data[0], // category
				$data[1], // name
				$newVer // version
			)
		);
	}
}

$jsonArr = array();
foreach (glob("*.{png,gif,apng}", GLOB_BRACE) as $file) {
	if (!is_dir($file)) {
		$data = explode("_", $file);

		$jsonArr[$data[0]]["name"] = $data[0];
		$jsonArr[$data[0]]["data"][] = array(
			"name" => $data[1],
			"version" => intval(explode(".", $data[2])[0]),
			//https://wdg.github.io/Apps/pJustSelfie/files.json
			"url" => "https://wdg.github.io/Apps/pJustSelfie/{$file}",
		);
	}
}

// Swift JSON Decoder fix.
$defArray = array();
foreach ($jsonArr as $key => $value) {
	$defArray[] = $value;
}
$jsonArr = $defArray;
// EOF Swift JSON Decoder fix.

echo $jsonArr = json_encode($jsonArr);
file_put_contents("files.json", $jsonArr);

echo "Generated index" . PHP_EOL;
exit;
