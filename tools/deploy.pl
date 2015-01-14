#!/usr/bin/perl
use strict;
use warnings;

use Getopt::Long;
use Net::FTP;
use Cwd;

use CSS::Minifier;
use JavaScript::Minifier;

use File::Copy qw(move);

use feature 'say';

my %AUTHORISED = map { $_ => 1 } qw(js css img);

my($action, $host, $login, $password, $directory, $output, $help, @to_compress);

GetOptions(
    'action|a=s'     => \$action,
    'host|h=s'       => \$host,
    'login|l=s'      => \$login,
    'password|p=s'   => \$password,
    'directory|d=s'  => \$directory,
    'output|o=s'     => \$output,
    'help|h'         => \$help);

sub display_help
{
    die <<END;
usage : deploy.pl -a [see|update|clean]
                  -h HOST NAME
                  -l LOGIN NAME
                  -p PASSWORD
                  -d DIRECTORY
		  -o OUTPUT
                  -h this help
END
}

display_help() if $help;

# action by default is update
$action //= 'update';
$directory //= cwd();
$host //= 'divebartheband.com';


sub read_directory
{
    my($directory, $files, $authorised) = @_;

    # we list the files to send
    my $filehandle;

    opendir($filehandle, $directory)
	or die "impossible to open '$directory' : $!";

    while (readdir($filehandle))
    {
	my $filename = $_;
	my $url = "$directory/$filename";

	# we don't take any hidden file/directory
	next if $filename =~ /^\./;

	if (-d $url and
	    (exists($AUTHORISED{$filename}) or defined $authorised))
	{
	    read_directory($url, $files, 1);
	}
	elsif (-f $url and $filename =~
	       /\.(html|php|js|css|eot|svg|ttf|woff|png|jpg|gif)+$/)
	{
	    # we detect all js & css files
	    push(@to_compress, $directory, $filename)
		if $filename =~ /\.(js|css)+$/ and $filename !~/\.min\./;

	    $files->{$directory} //= {};
	    $files->{$directory}{$filename} = undef;
	}
    }

    close($filehandle);
}

sub replace_link
{
    my($src, $dst) = @_;

    unlink($src);
    symlink($dst, $src);
}

my %files;
read_directory($directory, \%files);

if ($action eq 'see')
{
    use Data::Dumper;
    say Dumper \%files;
    exit;
}

if ($action eq 'clean')
{
}

unless ($action eq 'update')
{
    die "unknown action '$action'!\n";
}

unless (defined $login and defined $password and defined $host)
{
    display_help();
}

my @to_delete;

my $action_before = sub
{
    # we save the html file
    move "divebar.html", "divebar.html.sav";

    my $html;

    # we get html content
    open($html, "divebar.html.sav") or die "file 'divebar.html' not found!";
    my @lines = <$html>;
    close $html;

    my @new_lines;

    while (@to_compress)
    {
	my($directory, $filename) = splice(@to_compress, 0, 2);

	my $extension;
	if ($filename =~ s/\.(\w{2,3})+$//)
	{
	    $extension = $1;
	}

	my $new_filename = "$filename.min.$extension";
	$filename .= ".$extension";

	my($in, $out);

	open($in, "$directory/$filename") or die;
	open($out, ">$directory/$new_filename") or die;

	# we handle css file
	if ($extension eq 'css')
	{
	    CSS::Minifier::minify(input => $in, outfile => $out);
	}
	# we handle js file
	elsif ($extension eq 'js')
	{
	    JavaScript::Minifier::minify(input => $in, outfile => $out);
	}

	close($in);
	close($out);

	# we replace in the list
	delete $files{$directory}{$filename};
	$files{$directory}{$new_filename} = undef;

	# we replace in the html file
	my $i = 0;
	foreach (@lines)
	{
	    $_ =~ s/$filename/$new_filename/g;
	    $lines[$i++] = $_;
	}

	# we remove this file � the end of the process
	push(@to_delete, "$directory/$new_filename");
    }

    open($html, ">divebar.html") || die "file 'divebar.html' not found!";
    print {$html} @lines;
    close($html);
};

my $action_after = sub
{
    # we save back the html file
    move "divebar.html.sav", "divebar.html";

    foreach my $file (@to_delete)
    {
	unlink $file;
    }
};

# we try to establish connection
my $ftp = Net::FTP->new($host, Passive => 1)
    or die "Cannot connect to '$host': $@";

# we try to login
$ftp->login($login, $password)
    or die "Cannot login " . $ftp->message;

say("connected to $host!");

# Place both servers into the correct transfer mode.
# In this case I'm using ASCII.
$ftp->ascii() or die  "Can't set ASCII mode: $!";

# we link the default script file to external
if (defined $action_before) {
    $action_before->();
}

my $size = length($directory);

while (my($cwd, $files) = each %files)
{
    $cwd = substr($cwd, $size);

    my $destination = ((defined $output) ? "/$output" : "") . $cwd;

    if ($cwd eq '')
    {
	$cwd = '/';
    }
    else
    {
	# we create the directory
	$ftp->mkdir($destination, 1);
    }

    say "change directory '$destination'";
    $ftp->cwd($destination)
     	or die "Cannot change working directory ", $ftp->message;

    # we write the file
    while (my($file, undef) = each %$files)
    {
	# we set binary mode
	$ftp->binary;

	$ftp->put("$directory/$cwd/$file");
	say "send $file";
    }
}

if (defined $action_after) {
    $action_after->();
}

# we close connection
$ftp->close;
$ftp->quit;