'use client';

import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useAuthStore } from '@/store/authStore';
import { apiClient } from '@/services/apiClient';
import { useRouter } from 'next/navigation';
import { Loader2 } from 'lucide-react';

const loginSchema = z.object({
  username: z.string().min(1, 'Username is required'),
  password: z.string().min(1, 'Password is required'),
});

type LoginFormValues = z.infer<typeof loginSchema>;

export default function LoginForm() {
  const [error, setError] = useState('');
  const { login } = useAuthStore();
  const router = useRouter();

  const {
    register,
    handleSubmit,
    setValue,
    formState: { errors, isSubmitting },
  } = useForm<LoginFormValues>({
    resolver: zodResolver(loginSchema),
  });

  const onSubmit = async (data: LoginFormValues) => {
    try {
      setError('');
      const response = await apiClient.post('/auth/login', data);
      const { token, user } = response.data.data;
      
      login(user, token);
      
      if (user.role === 'hospital') {
        router.push('/hospital/inventory');
      } else {
        router.push('/blood/available');
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to login. Please try again.');
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      {error && (
        <div className="bg-red-50 text-red-600 p-3 rounded-md text-sm border border-red-100">
          {error}
        </div>
      )}
      <div>
        <label className="block text-sm font-medium text-gray-700">Username or Email</label>
        <div className="mt-1">
          <input
            {...register('username')}
            type="text"
            className={`block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-3 py-2 border ${errors.username ? 'border-red-500' : ''}`}
            placeholder="Enter username or email"
          />
          {errors.username && <p className="mt-1 text-sm text-red-600">{errors.username.message}</p>}
        </div>
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700">Password</label>
        <div className="mt-1">
          <input
            {...register('password')}
            type="password"
            className={`block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm px-3 py-2 border ${errors.password ? 'border-red-500' : ''}`}
            placeholder="Enter password"
          />
          {errors.password && <p className="mt-1 text-sm text-red-600">{errors.password.message}</p>}
        </div>
      </div>

      <button
        type="submit"
        disabled={isSubmitting}
        className="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-70"
      >
        {isSubmitting ? <Loader2 className="animate-spin h-5 w-5" /> : 'Sign In'}
      </button>

      <div className="mt-6 pt-6 border-t border-gray-100">
        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Demo Credentials (Click to prefill)</p>
        <div className="flex gap-3">
          <button
            type="button"
            onClick={() => {
              setValue('username', 'admin@medanta.in');
              setValue('password', '12345678');
            }}
            className="flex-1 text-xs bg-gray-50 text-gray-600 hover:bg-gray-100 py-2 px-2.5 rounded border border-gray-200 transition-colors font-medium text-center"
          >
            🏥 Hospital Admin
          </button>
          <button
            type="button"
            onClick={() => {
              setValue('username', 'neeleshbaghel40@gmail.com');
              setValue('password', '12345678');
            }}
            className="flex-1 text-xs bg-gray-50 text-gray-600 hover:bg-gray-100 py-2 px-2.5 rounded border border-gray-200 transition-colors font-medium text-center"
          >
            👤 General User
          </button>
        </div>
      </div>
    </form>
  );
}
