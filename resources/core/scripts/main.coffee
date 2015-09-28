$ ->
  'use strict'

  #-------------------------------------------------
  # Change language
  #-------------------------------------------------
  $languageSwitcher = $ '#js-language-switcher'
  $languageSwitcher.change ->
    window.location = @.value
