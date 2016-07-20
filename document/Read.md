1. 博客访恩量统计
2. es同步问题

[
	{"keys": ["ctrl+shift+r"], "command": "reindent" , "args":{"single_line": false}},
	{ "keys": ["f1"], "command": "goto_documentation" },
	 { "keys": ["ctrl+alt+d"], "command": "delete_trailing_spaces" },
    	{ "keys": ["ctrl+alt+o"], "command": "toggle_trailing_spaces" },
    	{ "keys": ["ctrl+alt+z"], "command": "alignment" },
]

* */2 * * * curl http://blog.com:8890/index.php/Home/Sync/syncBlogClink
* */12 * * * curl http://blog.com:8890/index.php/Home/Sync/syncBlog



#!/bin/bash

    cd /home/pzr/
    /opt/lampp/bin/php test.php
    echo "run: `date "+%Y-%m-%d %H:%M:%S"`"
