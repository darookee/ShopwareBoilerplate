#!/bin/sh

NEWNAME=${1:-Boilerplate}
NEWTYPE=${2:-Frontend}

echo "Creating ${NEWTYPE}-Plugin ${NEWNAME}"

sed -i "s/Frontend_Boilerplate/${NEWTYPE}_Boilerplate/g" Bootstrap.php
find . ! -path "*./.*" -type f -exec sed -i "s/Boilerplate/${NEWNAME}/g" {} \;
find . ! -path "*./.*" -type f -exec sed -i "s/boilerplate/${NEWNAME,,}/g" {} \;
mv Views/backend/boilerplate Views/backend/${NEWNAME,,} -f
mv Views/frontend/plugins/boilerplate Views/frontend/plugins/${NEWNAME,,} -f
mv Controllers/BoilerplateFrontend.php Controllers/${NEWNAME}Frontend.php -f
echo "Shopware ${NEWTYPE}-Plugin ${NEWNAME}" > README.md
rm setup.sh -f
