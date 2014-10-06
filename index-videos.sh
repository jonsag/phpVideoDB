#!/bin/bash
echo

# location of configuration file
# this must correlate to line in configurationfile at install time
CONFFILE="/etc/jsvideos/jsvidprevs.conf"
CONFFILE="$HOME/projects/jsvideos/trunk/jsvideos.conf"

# version of this script
VERSION="0.1"

# checking for config and reading it
if [ -e $CONFFILE ]; then
    source $CONFFILE
else
    echo "###################################################################"
    echo "You do not have a config file at $CONFFILE"
    echo "Make sure it's in it's right place, and the try again!"
    echo "Exiting..."
    echo "###################################################################"
    exit 1
fi

# starting up some variables
TIME=$(date +%y%m%d-%H%M%S)
LOGFILE=$LOGDIR/$LOGNAME-$TIME.log
THISDIR=$(dirname $0)

# check for temporary directory
if [ -d $TEMPDIR ]; then
    echo "##########################################################################"
    echo -e $GREEN"Temporary directory $TEMPDIR exists" $RESET
    echo -e $YELLOW"Cleaning out old temp files..." $RESET
    rm $TEMPDIR/$TEMPPREFIX*
    echo "##########################################################################"
else
    echo "##########################################################################"
    echo -e $RED"Temporary directory $TEMPDIR does not exist" $RESET
    echo -e $YELLOW"Creating it..." $RESET
    mkdir -p $TEMPDIR
    echo "##########################################################################"
fi
echo

# where are we
THISDIR=$(pwd)
echo "Indexing directory $THISDIR"
echo

# find videofiles and removing characters in the beginning
for TYPE in $FILETYPES
do
    find . -maxdepth 1 -iname "*.$TYPE" >> $TEMPDIR/$TEMPPREFIX.files1
    sed 's/^..//' $TEMPDIR/$TEMPPREFIX.files1 > $TEMPDIR/$TEMPPREFIX.files
done

echo "Found these files:"
cat $TEMPDIR/$TEMPPREFIX.files
echo

for FILE in `cat $TEMPDIR/$TEMPPREFIX.files`; do
    ((COUNTER++))
    echo "Processing file #$COUNTER: $FILE"

# md5sum
    echo -n "Calculating hash... "
    MD5SUM=$(md5sum $FILE | gawk '{ print $1 }')
    echo $MD5SUM

# length
    DUR=$(mediainfo '--Inform=General;%Duration/String3%' $FILE)
    HOURS=$(echo $DUR | gawk -F: '{ print $1 }')
    if [ $HOURS != "00" ]; then
	HOURS=$(echo $HOURS | sed 's/0*//')
    fi
    MINUTES=$(echo $DUR | gawk -F: '{ print $2 }')
    if [ $MINUTES != "00" ]; then
	MINUTES=$(echo $MINUTES | sed 's/0*//')
    fi
    SECONDS=$(echo $DUR | gawk -F: '{ print $3 }' | gawk -F. '{ print $1 }')
    if [ $SECONDS != "00" ]; then
	SECONDS=$(echo $SECONDS | sed 's/0*//')
    fi
    let "LENGTH = $HOURS*3600+$MINUTES*60+$SECONDS"
    echo "Length: $LENGTH seconds"
        
# height
    HEIGHT=$(mediainfo '--Inform=Video;%Height%' $FILE)
    echo "Height: $HEIGHT pixels"

# width
    WIDTH=$(mediainfo '--Inform=Video;%Width%' $FILE)
    echo "Width: $WIDTH pixels"
    
# size
    SIZE=$(ls -l $FILE | gawk '{ print $5 }')
#SIZE=$(mediainfo '--Inform=General;%FileSize/String%' $FILE)
    echo "Filesize: $SIZE bytes"

# aspect
    ASPECT=$(mediainfo '--Inform=Video;%AspectRatio/String%' $FILE)
    echo "Aspect Ratio: $ASPECT"
    
# videoCodec
    VIDEOCODEC=$(mediainfo '--Inform=Video;%CodecID/Hint%' $FILE)
    if [ -z "$VIDEOCODEC" ] || [ "$VIDEOCODEC" == "Microsoft" ]; then
        VIDEOCODEC=$(mediainfo '--Inform=Video;%Format%' $FILE)
    fi
    echo "Video Codec: $VIDEOCODEC"

# audioCodec    
    AUDIOCODEC=$(mediainfo '--Inform=Audio;%CodecID/Hint%' $FILE)
    if [ -z "$AUDIOCODEC" ]; then
        AUDIOCODEC=$(mediainfo '--Inform=Audio;%Format%' $FILE)
    fi
    echo "Audio Codec: $AUDIOCODEC"

# videoBitrate
    VIDEOBITRATE=$(mediainfo '--Inform=Video;%BitRate/String%' $FILE)
    echo "Video Bitrate: $VIDEOBITRATE"

# audioBitrate
    AUDIOBITRATE=$(mediainfo '--Inform=Audio;%BitRate/String%' $FILE)
    echo "Audio Bitrate: $AUDIOBITRATE"

# overallBitrate
    OVERALLBITRATE=$(mediainfo '--Inform=General;%BitRate/String%' $FILE)
    echo "Overall Bitrate: $OVERALLBITRATE"
    
# frameRate
    FRAMERATE=$(mediainfo '--Inform=Video;%FrameRate/String%' $FILE)
    echo "Frame Rate: $FRAMERATE"    
    echo

# check if file already in database
    echo -n "Checking if file is already in database... "
    echo "SELECT * from md5sum where username = '$MD5SUM';" >> < $TEMPDIR/$TEMPPREFIX.sql
    mysql --host=$DBHOST --user=$DBUSER --password=$DBPASS $DBNAME < $TEMPDIR/$TEMPPREFIX.sql

    rm $TEMPDIR/$TEMPPREFIX.sql
# writing to mysql
    echo -n "Writing to mySQL... "
    echo "INSERT INTO $VIDEOTBL (fileName,md5sum,length,height,width,size,aspect,videoCodec,audioCodec,videoBitrate,audioBitrate,overallBitrate,frameRate)" >> $TEMPDIR/$TEMPPREFIX.sql
    echo "VALUES('$FILE','$MD5SUM',$LENGTH,$HEIGHT,$WIDTH,$SIZE,'$ASPECT','$VIDEOCODEC','$AUDIOCODEC','$VIDEOBITRATE','$AUDIOBITRATE','$OVERALLBITRATE','$FRAMERATE');" >> $TEMPDIR/$TEMPPREFIX.sql

    mysql --host=$DBHOST --user=$DBUSER --password=$DBPASS $DBNAME < $TEMPDIR/$TEMPPREFIX.sql

    if [ $? == 0 ]; then
        echo "Done"
    else
        echo "ERROR"
        exit 1
    fi
    echo

    rm $TEMPDIR/$TEMPPREFIX.sql

done
