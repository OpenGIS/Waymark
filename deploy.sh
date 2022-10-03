#! /bin/bash
#
# Script to deploy from Github to WordPress.org Plugin Repository
# A modification of a number of different sources:
# @link https://github.com/deanc/wordpress-plugin-git-svn
# @link https://github.com/GaryJones/wordpress-plugin-svn-deploy
# @link https://github.com/thenbrent/multisite-user-management/blob/master/deploy.sh
#
# Accompanying Tutorial Here:
# @link https://ericbusch.net/?p=106

SVNUSER="morehawes"
PLUGINSLUG="waymark"
MAINFILE="Waymark.php"

# main config, set off of plugin slug
CURRENTDIR=`pwd`
CURRENTDIR="$CURRENTDIR"

# git config
GITPATH="$CURRENTDIR/" # this file should be in the base of your git repository

# svn config
SVNPATH="/tmp/$PLUGINSLUG" # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="http://plugins.svn.wordpress.org/$PLUGINSLUG/" # Remote SVN repo on WordPress.org, with no trailing slash

# Let's begin...
echo ".........................................."
echo
echo "Preparing to deploy WordPress plugin"
echo
echo ".........................................."
echo

# Check version in readme.txt is the same as plugin file
NEWVERSION1=`grep "^Stable tag" $GITPATH/readme.txt | awk -F' ' '{print $3}'`
echo "readme version: $NEWVERSION1"
NEWVERSION2=`grep "^Version" $GITPATH/$MAINFILE | awk -F' ' '{print $2}'`
echo "$MAINFILE version: $NEWVERSION2"

if [ "$NEWVERSION1" != "$NEWVERSION2" ]; then echo "Versions don't match. Exiting...."; exit 1; fi

echo "Versions match in readme.txt and PHP file. Let's proceed..."

cd $GITPATH
echo -e "Enter a commit message for this new version: \c"
read COMMITMSG

echo
echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

echo "Ignoring specific files and deployment script"
svn propset svn:ignore "deploy.sh
README.md
Gruntfile.js
node_modules/
package.json
package-lock.json
.git
.gitignore" "$SVNPATH/trunk/"

#export git -> SVN
echo "Exporting the HEAD of master from git to the trunk of SVN"
git checkout-index -a -f --prefix=$SVNPATH/trunk/

echo "Changing directory to SVN and committing to trunk"
cd $SVNPATH/trunk/
# Add all new files that are not set to be ignored
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
svn commit --username=$SVNUSER -m "$COMMITMSG"

echo "Creating new SVN tag & committing it"
cd $SVNPATH
svn copy trunk/ tags/$NEWVERSION1/
cd $SVNPATH/tags/$NEWVERSION1
svn commit --username=$SVNUSER -m "Tagging version $NEWVERSION1"

echo "Removing temporary directory $SVNPATH"
rm -fr $SVNPATH/

echo "*** FIN ***"