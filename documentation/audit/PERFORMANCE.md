# Performance

## Database

### N+1

We can start to do some manual verfications across the application by reading the Database queries. One of the most
common mistakes is too to meet a N+1 problem by lazy loading some entity relations. This is a common mistake when you
are not used to Doctrine.

To solve this problem we can eager load the relationships instead, reducing the number of queries.

It does not seem to be any N+1 problem in the application.

### Select

Also, when we query a lot of data, it can use a lot of memory and CPU. We can reduce the memory cost by selecting only
the fields that we need.

### Indexes

When we query a lot of data, it can use a lot of memories and CPU.
We can reduce the CPU cost by adding indexes to the columns that are usually used to query data.
We have to keep in mind that indexes increases speed for querying data but
also decreases it when inserting data.

## Profiling

We can do some profiling of the application using a professional tool like Blackfire. The profiler allows us to get
information about what parts of the application use too much CPU or costs too much memory.

### Comparing legacy results to rewritten application results

When comparing results of the new application, using a newer version of symfony and PHP, we realise that the application
is usually slower and uses more memory.
At first, we could think we're doing something wrong, but we have to keep in mind
that the new application is using a newer version of Symfony.
When we look at the graph, we can see that the only parts
that require more use of CPU and memory aren't application code, but code from the autoloader or Symfony core.

This can partially be explained by the use of the reflection API in the new IoC from Symfony 4. Indeed, even if it
decreases the performance a bit, this is nothing critical. As compensation, the productivity of developers is greatly
improved.
