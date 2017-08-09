# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "laravel/homestead"

    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.hostname = "55hubs"
    config.vm.box_version = "0.6.0"
    config.vm.provision "shell", path: "vagrant_provison.sh", privileged: false

    # Use NFS for shared folders for better performance
    config.vm.synced_folder '.', '/vagrant', nfs: true
    config.vm.synced_folder "../55hubs-themes", "/55hubs-themes", nfs: true

end
