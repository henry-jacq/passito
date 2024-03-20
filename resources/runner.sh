#!/bin/bash

# This script will run the set of commands used for grunt
# Created on: 2023-04-19
# Updated on: 2024-01-07

# It will concat and minify the CSS files
function run_css()
{
    grunt concat:auth_css
    grunt concat:user_css
    grunt cssmin:auth_css
    grunt cssmin:user_css
}

# It will concat and minify the SCSS files
function run_scss()
{
    sleep 1
    grunt concat:scss
    grunt sass
    grunt cssmin:scss
}

# It will minify and obfuscate the javascript files
function run_js()
{
    grunt concat:js_app
    grunt concat:js_core
    grunt uglify
    grunt obfuscator
}

# It will copy files to public
function copy_to_public()
{
    grunt copy
}

function check_grunt_file_exists()
{
    if [[ ! -f './Gruntfile.js' ]]; then
        echo -e "[-] Gruntfile doesn't exist!\n"
        exit -1
    fi
}

function check_args()
{
    if [[ $1 != '-t' ]]
    then
        echo -e "[!] Usage: $0 -t <task>"
        echo -e "[!] Available tasks: [css, scss, js, copy, watch]\n"
        exit -1
    elif [[ $2 == '' ]]
    then
        echo -e "[!] Usage: $0 -t <task>"
        echo -e "[!] Available tasks: [css, scss, js, copy, watch]\n"
        exit -1
    fi
}

function main()
{
    echo -e "\tGrunt runner v0.4\n"
    check_grunt_file_exists
    check_args $*
    while getopts t: flag
    do
        case "${flag}" in
            t) task=${OPTARG};;
        esac

        if [[ $task == 'js' || $task == 'css' || $task == 'scss' || $task == 'copy' || $task == 'watch' ]]
        then
            echo -e "-> Running grunt tasks for $task\n"
            if [[ $task == 'js' ]]; then
                run_js
                if [[ $? == 0 ]]; then
                    echo -e "[+] Tasks for $task done successfully\n"
                fi
            elif [[ $task == 'css' ]]; then
                run_css
                if [[ $? == 0 ]]; then
                    echo -e "[+] Tasks for $task done successfully\n"
                fi
            elif [[ $task == 'scss' ]]; then
                run_scss
                if [[ $? == 0 ]]; then
                    echo -e "[+] Tasks for $task done successfully\n"
                fi
            elif [[ $task == 'copy' ]]; then
                copy_to_public
                if [[ $? == 0 ]]; then
                    echo -e "[+] Tasks for $task done successfully\n"
                fi
            elif [[ $task == 'watch' ]]; then
                grunt watch
            fi
        else
            echo -e "[-] Provide valid task name!\n"
            exit -1;
        fi
    done
}

main $*