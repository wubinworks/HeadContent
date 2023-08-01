/**
 * Copyright © Wubinworks. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
	map: {
        "*": {
			hljs: "Wubinworks_InjectHead/js/highlight.min",
            CodeMirror: "Wubinworks_InjectHead/js/codemirror-5.65.12/lib/codemirror",
            CodeMirrorHtmlmixed: "Wubinworks_InjectHead/js/codemirror-5.65.12/mode/htmlmixed/htmlmixed"
        }
    },
	
	shim: {
		"Wubinworks_InjectHead/js/highlight.min": {
			exports: "hljs"
		}
	}
};
