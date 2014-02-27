<?php if(!defined('KIRBY')) exit ?>

#Home
#Used for categories

title: Home
pages: 
  sort: flip
files: false
fields:
  title: 
    label: Title
    type:  text
  subtitle: 
    label: Subtitle
    type: text
  articles:
    label: Articles
    help: Use JSON
    type: textarea
    size: large
