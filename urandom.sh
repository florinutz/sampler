#!/usr/bin/env bash

dd if=/dev/urandom count=10 bs=1024 | base64 | php sampler.php
