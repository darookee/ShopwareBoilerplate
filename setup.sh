#!/bin/sh

NEWNAME=${1:-Boilerplate}
NEWTYPE=${2:-Frontend}

echo "Creating Plugin ${NEWNAME} for ${NEWTYPE}"

if hash git 2>&- /dev/null; then
    git checkout -b ${NEWNAME,,}
fi

sed -i "s/Backend_Boilerplate/${NEWTYPE}_Boilerplate/g" Bootstrap.php
find . ! -path "*./.*" -type f -exec sed -i "s/Boilerplate/${NEWNAME}/g" {} \;
find . ! -path "*./.*" -type f -exec sed -i "s/boilerplate/${NEWNAME,,}/g" {} \;
git mv Views/backend/boilerplate Views/backend/${NEWNAME,,} -f
git mv Views/frontend/plugins/boilerplate Views/frontend/plugins/${NEWNAME,,} -f
git mv Controllers/BoilerplateBackend.php Controllers/${NEWNAME}Backend.php -f
git mv Controllers/BoilerplateFrontend.php Controllers/${NEWNAME}Frontend.php -f
echo "Shopware ${NEWTYPE}-Plugin ${NEWNAME}" > README.md
git rm setup.sh -f
git commit -a -m "Setup ${NEWNAME} initial commit"
