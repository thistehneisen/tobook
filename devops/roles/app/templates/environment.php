<?php

return
{%- if env == 'stag' %}
 'stag';
{% elif env == 'prod' %}
 'prod';
{% endif -%}
