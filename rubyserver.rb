require 'rubygems'
require 'socket'
require 'cgialt'
require 'pp'
require 'amatch'
include Amatch

  #Read in the saved results on startup
  startime = Time.now.to_i
  $hashlist = Array[]
  file = File.new("file", "r")
  while (line = file.gets)
  	variables = line.strip.split(%r[ ])
        $hashlist << variables;
  end
  puts "Datafile loaded : #{$hashlist.size} elements in #{Time.now.to_i - startime} seconds."

  server = TCPServer.new('66.160.197.233', 9090)
  while (session = server.accept)
	session.print "HTTP/1.1 200/OK\rContent-type: text/xml\r\n\r\n"

 
	request = session.gets
	#puts request

	#Get variables, store them in a hash
	hash = {}
	incoming = request.split(%r[&|\?| ])
  	incoming.each do |value|
	if (value.index('=') != nil)
       		variables = value.split(%r[=])
		hash[variables[0]] = variables[1]
 	end	
  end	

  if hash["action"] == "FindClosest"
	if hash["hash"] != nil
		if hash["hashtype"] == "ImageHash"
			puts "Looking for IMG hashmatch #{hash['hash']}."
			session.puts hammfind(hash["hash"])	
		end
		if hash["hashtype"] == "MD5"
                	puts "Looking for MD5 hashmatch #{hash['hash']}."
                	session.puts hashfind(hash["hash"])
       		 end
	end
	
  end
  if hash["action"] == "SaveDB"
	if hash["filename"] != nil
		startime = Time.now.to_i
		writefile(hash["filename"])
		session.puts "Saved."	
		puts "Datafile Saved: #{$hashlist.size} elements in #{Time.now.to_i - startime} seconds."
	end
  end

  session.puts "Goodbye."
  session.close


  def writefile (filename)
  
  	file = File.new("/tmp/#{filename}","w")
 	$hashlist.each do |value|
		file.puts "#{value[0]} #{value[1]} #{value[2]}"
      	end
 

  end

  def hashfind (hash)
 	$hashlist.each do |value|
		if value[2] == hash
			puts "MD5 Match = #{value[0]}"
			return value[0]
		end
        end
	puts "No Match for #{hash}."
	return ""
  end

  def hammfind (hash)

	ham1 = Hamming.new(hash);
	results = Array[]
	$hashlist.each do |value|
        	matchvalue = ham1.match(value[1])
        	if (matchvalue <= 2)
                	result = Array[]
                	result[0] = value[0]   # Filename
                	result[1] = value[1]   # Hash
                	result[2] = matchvalue # Hammingvalue
                	results << result;
        	end
	end

	if (results.size > 0)
        	sorted = results.sort_by { |hammingvalue| hammingvalue[2] }
        	puts "Closest match is #{sorted[0][0]}."
		return sorted[0][0]; 
	else
        	puts "No Match for #{hash}."
		return "";
	end
  end

end
