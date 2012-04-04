#!/bin/sh

NEWNAME=${1:-Boilerplate}
NEWTYPE=${2:-Frontend}

echo "Creating Plugin ${NEWNAME} for ${NEWTYPE}"

if hash git 2>&- /dev/null; then
    git checkout -b ${NEWNAME,,}
fi

find . ! -path "*./.*" -type f -exec sed -i "s/Boilerplate/${NEWNAME}/g" {} \;
find . ! -path "*./.*" -type f -exec sed -i "s/boilerplate/${NEWNAME,,}/g" {} \;
sed -i "s/Backend_Boilerplate/${NEWTYPE,,}_${NEWNAME,,}/g" Bootstrap.php
git mv templates/backend templates/${NEWTYPE,,} -f
git mv templates/${NEWTYPe,,}/boilerplate templates/${NEWTYPE,,}/${NEWNAME,,} -f
git mv BoilerplateBackend.php ${NEWNAME}Backend.php -f
git mv BoilerplateFrontend.php ${NEWNAME}Frontend.php -f
echo "Shopware ${NEWTYPE}-Plugin ${NEWNAME}" > README.md
git rm setup.sh -f

