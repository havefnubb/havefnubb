; Weight of the result of search
search_subject_weight=1
search_content_weight=2
search_tag_weight=3

;dao from which you read the datas you want to 'inject' in table of the search engine
dao = havefnubb~posts

; a list of method that can be use to perfom search, like "searchIn" + Authors , or Forums, or Words, or what ever
; have a loot at search_in.class.php
perform_search_in = words,forums,authors