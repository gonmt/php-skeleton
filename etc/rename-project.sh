#!/bin/sh

[ -z "$1" ] && echo "Company name cannot be empty" && exit 1
[ -z "$2" ] && echo "App name cannot be empty" && exit 1

# Funciones para convertir a mayúsculas y minúsculas
to_lower() {
    echo "$1" | tr '[:upper:]' '[:lower:]'
}

to_upper() {
    echo "$1" | tr '[:lower:]' '[:upper:]'
}

new_company_name=$1
new_app_name=$2

old_company_name=CompanyName
old_app_name=FirstApp

repository_url=$(git config --get remote.origin.url)
repository_name=$(basename -s .git $repository_url)

old_company_name_lower=$(to_lower "$old_company_name")
old_app_name_lower=$(to_lower "$old_app_name")
old_app_name_upper=$(to_upper "$old_app_name")

new_company_name_lower=$(to_lower "$new_company_name")
new_app_name_lower=$(to_lower "$new_app_name")
new_app_name_upper=$(to_upper "$new_app_name")

sed -i "s/REPOSITORY_NAME/$repository_name/g" README.md
sed -i "s|REPOSITORY_URL|$repository_url|g" README.md

find . -type f ! -path "./.git/*" -exec sed -i "s/$old_company_name/$new_company_name/g" {} \;
find . -type f ! -path "./.git/*" -exec sed -i "s/$old_company_name_lower/$new_company_name_lower/g" {} \;

find . -type f ! -path "./.git/*" -exec sed -i "s/$old_app_name/$new_app_name/g" {} \;
find . -type f ! -path "./.git/*" -exec sed -i "s/$old_app_name_lower/$new_app_name_lower/g" {} \;
find . -type f ! -path "./.git/*" -exec sed -i "s/$old_app_name_upper/$new_app_name_upper/g" {} \;

find . -depth -type f -name "*$old_app_name*" -exec sh -c 'mv "$1" "${1//'"$old_app_name"'/'"$new_app_name"'}"' _ {} \;
find . -depth -type d -name "*$old_app_name_lower*" -exec sh -c 'mv "$1" "${1//'"$old_app_name_lower"'/'"$new_app_name_lower"'}"' _ {} \;
