# BitBar Plugins

## What's BitBar

[BitBar](https://getbitbar.com), developed by [Mat Ryer (@matryer)](https://twitter.com/matryer), lets you put the output from any script/program in your macOS Menu Bar.

For more check the official [GitHub repo](https://github.com/matryer/bitbar).

## Installing plugins

Just download the plugin of your choice into your BitBar plugins directory and choose `Refresh` from one of the BitBar menus.

### Configure the refresh time

The refresh time is in the filename of the plugin, following this format:

    {name}.{time}.{ext}

  * `name` - The name of the file
  * `time` - The refresh rate (see below)
  * `ext` - The file extension

For example:

  * `date.1m.sh` would refresh every minute.

Most plugins will come with a default, but you can change it to anything you like:

  * 10s - ten seconds
  * 1m - one minute
  * 2h - two hours
  * 1d - a day

### Ensure you have execution rights

Ensure the plugin is executable by running `chmod +x plugin.sh`.