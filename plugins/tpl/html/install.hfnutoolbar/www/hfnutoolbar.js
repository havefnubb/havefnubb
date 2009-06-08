/* ***** BEGIN LICENSE BLOCK *****
 *
 * Copyright (c) 2004 Olivier Meunier and contributors. All rights
 * reserved.
 *
 * Adapté pour HaveFnuBB! par FoxMaSk
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * ***** END LICENSE BLOCK ***** */

function hfnutoolBar(textarea,bt_img_path)
{
	this.addButton		= function() {};
	this.addSpace		= function() {};
	this.draw			= function() {};
	this.btStrong		= function() {};
	this.btEm			= function() {};
	this.btQ			= function() {};
	this.btBquote		= function() {};
	this.btCode			= function() {};
	this.btLeft			= function() {};
	this.btCenter		= function() {};
	this.btRight		= function() {};
	this.btPre			= function() {};
	this.btHr			= function() {};
	this.btLink			= function() {};
	this.btFullLink		= function() {};
	this.btLineBreak	= function() {};
	this.btAcronym		= function() {};
	this.btImgLink		= function() {};
	this.btList			= function() {};
	this.btPara			= function() {};
	this.btDef			= function() {};


	if (!document.createElement) {
		return;
	}

	if ((typeof(document['selection']) == 'undefined') 
	&& (typeof(textarea['setSelectionRange']) == 'undefined')) {
		return;
	}

	var toolbar = document.createElement('div');
	toolbar.id = 'hfnutoolbar';

	function addButton(src, title, fn)
	{
		var i = document.createElement('img');
		i.src = src;
		i.title = title;
		i.onclick = function() { try { fn() } catch (e) { } return false };
		i.tabIndex = 400;
		toolbar.appendChild(i);
		addSpace(2);
	}

	function addSpace(w)
	{
		s = document.createElement('span');
		s.style.padding='0 '+w+'px 0 0';
		s.appendChild(document.createTextNode(' '));
		toolbar.appendChild(s);
	}

	function encloseSelection(prefix, suffix, fn)
	{
		textarea.focus();
		var start, end, sel, scrollPos, subst;

		if (typeof(document['selection']) != 'undefined') {
			sel = document.selection.createRange().text;
		} else if (typeof(textarea['setSelectionRange']) != 'undefined') {
			start = textarea.selectionStart;
			end = textarea.selectionEnd;
			scrollPos = textarea.scrollTop;
			sel = textarea.value.substring(start, end);
		}

		if (sel.match(/ $/)) { // exclude ending space char, if any
			sel = sel.substring(0, sel.length - 1);
			suffix = suffix + " ";
		}

		if (typeof(fn) == 'function') {
			var res = (sel) ? fn(sel) : fn('');
		} else {
			var res = (sel) ? sel : '';
		}

		subst = prefix + res + suffix;

		if (typeof(document['selection']) != 'undefined') {
			var range = document.selection.createRange().text = subst;
			textarea.caretPos -= suffix.length;
		} else if (typeof(textarea['setSelectionRange']) != 'undefined') {
			textarea.value = textarea.value.substring(0, start) + subst +
			textarea.value.substring(end);
			if (sel) {
				textarea.setSelectionRange(start + subst.length, start + subst.length);
			} else {
				textarea.setSelectionRange(start + prefix.length, start + prefix.length);
			}
			textarea.scrollTop = scrollPos;
		}
	}

	function draw()
	{
		textarea.parentNode.insertBefore(toolbar, textarea);
	}


	// ---

	function btStrong(label)
	{
		addButton(bt_img_path+'text_bold.png',label,
		function() {
			encloseSelection('__','__');
		});
	}

	function btEm(label)
	{
		addButton(bt_img_path+'text_italic.png',label,
		function() {
			encloseSelection('\'\'','\'\'');
		});
	}


	function btQ(label)
	{
		addButton(bt_img_path+'text_quote.png',label,
		function() {
			encloseSelection('^^','^^');
		});
	}

	function btBquote(label)
	{
		addButton(bt_img_path+'text_quote.png',label,
		function() {
			encloseSelection('>','');
		});
	}

	function btCode(label)
	{
		addButton(bt_img_path+'text_code.png',label,
		function() {
			encloseSelection('@@','@@');
		});
	}

	function btLeft(label)
	{
		addButton(bt_img_path+'text_align_left.png',label,
		function() {
			encloseSelection('__','__');
		});
	}

	function btCenter(label)
	{
		addButton(bt_img_path+'text_align_center.png',label,
		function() {
			encloseSelection('__','__');
		});
	}

	function btRight(label)
	{
		addButton(bt_img_path+'text_align_right.png',label,
		function() {
			encloseSelection('__','__');
		});	
	}

	function btJustify(label)
	{
		addButton(bt_img_path+'text_align_justify.png',label,
		function() {
			encloseSelection('__','__');
		});	
	}

	function btPre(label)
	{
		addButton(bt_img_path+'text_source.png',label,
		function() {
			encloseSelection('<code>','</code>');
		});
	}

	function btList(label)
	{
		addButton(bt_img_path+'text_list_numbers.png',label,
		function() {
			encloseSelection('','',
			function(str) {
				label = "*" + label + "\n";
				return label;
			});
		});
	}
	
	function btPara(label) {
		addButton(bt_img_path+'text_para.png',label,		
		function() {
			encloseSelection('','',
			function(str) {
				label = "\n" + "\n";
				return label;
			});
		});
	}

	function btDef(label,msg_word,msg_def)
	{
		addButton(bt_img_path+'text_def.png',label,
		function() {
			encloseSelection(';','',
			function(str) {
				var word = window.prompt(msg_word,str);
				if (!word) { return str; }

				var def = window.prompt(msg_def,str);
				if (!def) { return str; }
				
				word = word + ' : '+ def+"\n";
				
				return word ;
			});
		});
	}

	
	function btImgLink(label,msg_url,msg_alternative,msg_align,msg_description)
	{
		addButton(bt_img_path+'text_image.png',label,
		function() {
			encloseSelection('((','))',
			function(str) {
				var href = window.prompt(msg_url,str);
				if (!href) { return str; }
				final_str = href;
				
				var alternative_text = window.prompt(msg_alternative,'');				
				
				var align = window.prompt(msg_align,'');
				if (!align) { align = 'L'; }
				
				var description = window.prompt(msg_description,'');

				if (align) {
					final_str = final_str + '|' + align;
				}
				
				if (alternative_text) {					
					final_str = final_str + '|' + alternative_text;					
				} 

				if (description) {					
					final_str = final_str + '|' + description;					
				} 
				
				return final_str;
			

			});
		});
	}

	function btHr(label)
	{
		addButton(bt_img_path+'text_hr.png',label,
		function() {
			encloseSelection('====','');
		});
	}

	function btLink(label,msg_url,msg_label)
	{
		addButton(bt_img_path+'text_link.png',label,
		function() {
			encloseSelection('[[',']]',
			function(str) {
				var href = window.prompt(msg_url,str);
				if (!href) { return str; }

				var label = window.prompt(msg_label,str);

				if (label) {
					return label+'|'+href;
				} else {
					return href;
				}
			});
		});
	}
	
	function btFullLink(label,msg_url,msg_label,msg_lang)
	{
		addButton(bt_img_path+'text_full_link.png',label,
		function() {
			encloseSelection('[[',']]',
			function(str) {
				var href = window.prompt(msg_url,str);
				if (!href) { return str; }

				var label = window.prompt(msg_label,str);
				if (!label) {return str; }
				
				var lang = window.prompt(msg_lang,str);
				if (!lang) {return str; }
				
				return label+'|'+href + '|' + lang;
			});
		});
	}

	function btLineBreak(label)
	{
		addButton(bt_img_path+'text_carriage_return.png',label,
		function() {
			encloseSelection('%%%','',
			function(str) {
				return str;
			});
		});
	}

	function btAcronym(label,msg_title,msg_label)
	{
		addButton(bt_img_path+'text_acronym.png',label,
		function() {
			encloseSelection('??','??',
			function(str) {
				var label = window.prompt(msg_label,str);
				if (!label) { return str; }

				var title = window.prompt(msg_title,'');

				if (title) {
					return label+'|'+title;
				} else {
					return label;
				}
			});
		});
	}


	// methods
	this.addButton		= addButton;
	this.addSpace		= addSpace;
	this.draw			= draw;
	this.btStrong		= btStrong;
	this.btEm			= btEm;
	this.btQ			= btQ;
	this.btBquote		= btBquote;
	this.btCode			= btCode;
	this.btLeft			= btLeft;
	this.btCenter		= btCenter;
	this.btRight		= btRight;
	this.btJustify		= btJustify;
	this.btPre			= btPre;
	this.btHr			= btHr;
	this.btLink			= btLink;
	this.btFullLink		= btFullLink;
	this.btLineBreak	= btLineBreak;
	this.btAcronym		= btAcronym;
	this.btImgLink		= btImgLink;
	this.btList			= btList;
	this.btPara			= btPara;
	this.btDef			= btDef;
	
}
