using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace dcapicsharp
{
    class DCApiExample
    {
        static void Main(string[] args)
        {

            String myHostName = "yourhost.localhost.com";
            String myApiKey = "your API Key";


            Console.WriteLine("This is the test of the DC API in C#");
            DCApi dcapi = new DCApi(myHostName, myApiKey);
            //dcapi.setOutPort(8443);  // local testing only

            bool isFirst = true;
            String specificOfferingId = null;

            List<Offering> offerings = dcapi.getAllOfferings();
            foreach (Offering offering in offerings)
            {
                Console.WriteLine("Found Offering Id " + offering.id);
                Console.WriteLine("      " + offering.title);
                if (isFirst)
                {
                    specificOfferingId = offering.id;
                }
                isFirst = false;
            }

            // Use the specific offering ID from above to test getOfferingById
            Console.WriteLine("Getting offering by id for id " + specificOfferingId);
            Offering oneOffering = dcapi.getOfferingById(specificOfferingId);
            Console.WriteLine("Got an offering: " + oneOffering.title);

            Console.WriteLine();
            Console.WriteLine("Testing get all users (Note that limit may be enforced here)");

            String specificUserEmail = null;
            String specificUserId = null;
            isFirst = true;

            List<User> users = dcapi.getAllUsers();
            foreach (User user in users)
            {
                Console.WriteLine("Found user " + user.id);
                if (isFirst)
                {
                    specificUserId = user.id;
                    specificUserEmail = user.email;  // we'll use these later in the filtering test
                }
                isFirst = false;
            }

            Console.WriteLine("\nTesting findUserById with id " + specificUserId);
            User oneUser = dcapi.getUserById(specificUserId);
            Console.WriteLine("Found user with id " + specificUserId + " and email " + oneUser.email);

            Console.WriteLine("\nTesting creation with random user email");
            String randomNum = new Random().Next().ToString();
            User randomUser = new User();
            randomUser.email = "randomUser" + randomNum + "@yourdomain.com";
            randomUser.firstName = "RandomUser";
            randomUser.lastName = randomNum;
            randomUser.password = randomNum + "P123";
            randomUser.locale = "en";
            Console.WriteLine("Trying to create user with email " + randomUser.email);

            dcapi.createUser(randomUser);
            Console.WriteLine("Created user with email " + randomUser.email);

            String modUserId = null;
            Console.WriteLine("\nTesting findUser with email filter");
            Dictionary<String, String> emailFilter = new Dictionary<String, String>();
            emailFilter.Add("email", randomUser.email);
            List<User> emailUsers = dcapi.getUsersWithFilter(emailFilter);
            foreach (User emailUser in emailUsers)
            {
                modUserId = emailUser.id;
                Console.WriteLine("Found user with id " + emailUser.id + " and email " + emailUser.email);
            }

            Console.WriteLine("\nModifying user with id " + modUserId + " (change firstname to randomModified)");
            Dictionary<String, String> modifiedUserFields = new Dictionary<String, String>();
            modifiedUserFields.Add("firstName", "randomModified");
            dcapi.modifyUser(modUserId, modifiedUserFields);
            Console.WriteLine("Modified user " + modUserId + " firstname");

            Console.WriteLine("\nDeleting random user created above with id " + modUserId);
            dcapi.deleteUser(modUserId);
            Console.WriteLine("Deleted user with id " + modUserId);

            Console.WriteLine("End of Line");
        }
    }
}
