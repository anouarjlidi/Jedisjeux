#!/usr/bin/env bash

source "$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/../../../bash/common.lib.sh"

print_header "Uploading logs" "Jedisjeux"
run_command "vendor/lakion/mink-debug-extension/travis/tools/upload-textfiles \"${JEDISJEUX_BUILD_DIR}/*.log\""

print_header "Uploading screenshots" "Jedisjeux"
run_command "IMGUR_API_KEY=4907fcd89e761c6b07eeb8292d5a9b2a vendor/lakion/mink-debug-extension/travis/tools/upload-screenshots \"${JEDISJEUX_BUILD_DIR}/*.png\""
